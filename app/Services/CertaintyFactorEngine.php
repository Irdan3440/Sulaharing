<?php

namespace App\Services;

use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Rule;

class CertaintyFactorEngine
{
    /**
     * Calculate CF for each disease based on user answers.
     *
     * @param array $answers [symptom_id => answer_index (0-3)]
     * @return array [
     *   'best_disease' => Disease,
     *   'best_cf' => float,
     *   'best_percentage' => float,
     *   'classification' => string,
     *   'total_score' => int,
     *   'details' => [...per-symptom detail],
     *   'all_diseases' => [...cf per disease],
     * ]
     */
    public function calculate(array $answers): array
    {
        $diseases = Disease::all();
        $symptoms = Symptom::whereIn('id', array_keys($answers))->get()->keyBy('id');
        $rules = Rule::with('symptom', 'disease')->get();

        $allResults = [];
        $details = [];
        $totalScore = 0;

        foreach ($diseases as $disease) {
            $cfCombine = 0;
            $diseaseRules = $rules->where('disease_id', $disease->id);
            $isFirst = true;

            foreach ($diseaseRules as $rule) {
                $symptomId = $rule->symptom_id;
                if (!isset($answers[$symptomId])) continue;

                $answerIndex = (int) $answers[$symptomId];

                // CF User: maps answer index (0-3) to certainty
                $cfUser = $this->mapAnswerToCfUser($answerIndex);

                // CF Pakar: MB and MD from rules table
                $mb = (float) $rule->mb;
                $md = (float) $rule->md;

                // CF Rule = MB - MD
                $cfRule = $mb - $md;

                // CF Combine = CF User × CF Rule
                $cfStep = $cfUser * $cfRule;

                // Sequential combination
                if ($isFirst) {
                    $cfCombine = $cfStep;
                    $isFirst = false;
                } else {
                    if ($cfCombine >= 0 && $cfStep >= 0) {
                        $cfCombine = $cfCombine + $cfStep * (1 - $cfCombine);
                    } elseif ($cfCombine < 0 && $cfStep < 0) {
                        $cfCombine = $cfCombine + $cfStep * (1 + $cfCombine);
                    } else {
                        $cfCombine = ($cfCombine + $cfStep) / (1 - min(abs($cfCombine), abs($cfStep)));
                    }
                }

                // Track detail for this symptom (only once per symptom)
                if ($disease->id === $diseases->first()->id || $disease->kode === 'D001') {
                    // We'll build details from the first pass
                }

                $details[$symptomId][$disease->id] = [
                    'symptom_id' => $symptomId,
                    'symptom_kode' => $rule->symptom->kode ?? '',
                    'symptom_nama' => $rule->symptom->nama ?? '',
                    'answer_index' => $answerIndex,
                    'answer_score' => $answerIndex,
                    'cf_user' => round($cfUser, 2),
                    'cf_pakar_mb' => round($mb, 2),
                    'cf_pakar_md' => round($md, 2),
                    'cf_rule' => round($cfRule, 2),
                    'cf_combine_step' => round($cfStep, 4),
                ];
            }

            $allResults[$disease->id] = [
                'disease' => $disease,
                'cf_result' => round($cfCombine, 4),
                'cf_percentage' => round($cfCombine * 100, 1),
            ];
        }

        // Calculate total BDI score
        foreach ($answers as $symptomId => $answerIndex) {
            $totalScore += (int) $answerIndex;
        }

        // Find best matching disease based on BDI score
        $classification = $this->classifyByBdiScore($totalScore);
        $bestDisease = $diseases->first(function ($d) use ($classification) {
            return strtolower($d->nama) === 'depresi ' . strtolower($classification);
        });

        $bestCf = $allResults[$bestDisease->id]['cf_result'] ?? 0;
        $bestPercentage = $allResults[$bestDisease->id]['cf_percentage'] ?? 0;

        // Build flat details array
        $flatDetails = [];
        foreach ($answers as $symptomId => $answerIndex) {
            $symptom = $symptoms->get($symptomId);
            $flatDetails[] = [
                'symptom_id' => $symptomId,
                'symptom_kode' => $symptom->kode ?? '',
                'symptom_nama' => $symptom->nama ?? '',
                'aspek' => $symptom->aspek ?? '',
                'answer_index' => (int) $answerIndex,
                'answer_score' => (int) $answerIndex,
                'cf_user' => round($this->mapAnswerToCfUser((int) $answerIndex), 2),
            ];
        }

        return [
            'best_disease' => $bestDisease,
            'best_cf' => $bestCf,
            'best_percentage' => $bestPercentage,
            'classification' => $classification,
            'total_score' => $totalScore,
            'details' => $flatDetails,
            'all_diseases' => $allResults,
        ];
    }

    /**
     * Map answer index (0-3) to CF User value.
     * 0 = no symptom (0.0), 1 = mild (0.4), 2 = moderate (0.7), 3 = severe (1.0)
     */
    protected function mapAnswerToCfUser(int $index): float
    {
        return match ($index) {
            0 => 0.0,
            1 => 0.4,
            2 => 0.7,
            3 => 1.0,
            default => 0.0,
        };
    }

    /**
     * Classify depression level by BDI-II total score.
     */
    public function classifyByBdiScore(int $score): string
    {
        if ($score <= 13) return 'Minimal';
        if ($score <= 19) return 'Ringan';
        if ($score <= 28) return 'Sedang';
        return 'Berat';
    }
}
