<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Symptom;
use App\Models\Disease;
use App\Models\Rule;

/**
 * Mesin Kalkulasi Certainty Factor (CF) yang dikombinasikan dengan BDI-II.
 *
 * Implementasi ini merujuk pada dokumen Skripsi resmi sistem SulaHaring:
 * - Pembobotan CF User standar (Tabel 3.7)
 * - Pengecualian gejala kritis G09 / Pikiran Bunuh Diri (Tabel 3.8)
 * - Klasifikasi diagnosis berdasarkan nilai CF Gabungan (Tabel 3.9)
 *
 * Rumus utama:
 *   CF Rule   = MB (Measure of Belief) - MD (Measure of Disbelief)
 *   CF(H,E)   = CF User × CF Rule
 *   CF Combine = CF_old + CF_new × (1 - CF_old)  [untuk CF >= 0]
 *
 * @package App\Services
 */
class CertaintyFactorEngine
{
    /**
     * Kode gejala kritis yang memiliki pembobotan khusus.
     * Berdasarkan Tabel 3.8 dokumen Skripsi (Pikiran untuk Bunuh Diri).
     */
    private const CRITICAL_SYMPTOM_CODE = 'G09';

    /**
     * Pembobotan standar CF User berdasarkan answer_index (Tabel 3.7 Skripsi).
     *
     * Index 0 = Tidak ada gejala    → 0.00
     * Index 1 = Gejala ringan       → 0.25
     * Index 2 = Gejala sedang       → 0.65
     * Index 3 = Gejala berat        → 1.00
     */
    private const CF_USER_STANDARD = [
        0 => 0.00,
        1 => 0.25,
        2 => 0.65,
        3 => 1.00,
    ];

    /**
     * Pembobotan khusus CF User untuk gejala kritis G09 (Tabel 3.8 Skripsi).
     * Bobot severity lebih tinggi karena sifat kritis gejala pikiran bunuh diri.
     *
     * Index 0 = Tidak ada pikiran    → 0.00
     * Index 1 = Pikiran ringan       → 0.30
     * Index 2 = Pikiran sedang       → 0.70
     * Index 3 = Pikiran kuat/aktif   → 1.00
     */
    private const CF_USER_G09 = [
        0 => 0.00,
        1 => 0.30,
        2 => 0.70,
        3 => 1.00,
    ];

    /**
     * Menghitung CF untuk setiap penyakit berdasarkan jawaban pengguna,
     * kemudian menentukan penyakit dengan CF tertinggi dan klasifikasinya.
     *
     * @param  array<int, int> $answers [symptom_id => answer_index (0-3)]
     * @return array{
     *   best_disease: ?Disease,
     *   best_cf: float,
     *   best_percentage: float,
     *   classification: string,
     *   total_score: int,
     *   details: array,
     *   all_diseases: array
     * }
     */
    public function calculate(array $answers): array
    {
        $diseases = Disease::all();
        $symptoms = Symptom::whereIn('id', array_keys($answers))->get()->keyBy('id');
        $rules    = Rule::with('symptom', 'disease')->get();

        $allResults = [];
        $totalScore = 0;

        // --- Fase 1: Hitung CF Gabungan untuk setiap penyakit ---
        foreach ($diseases as $disease) {
            $cfCombine   = 0.0;
            $diseaseRules = $rules->where('disease_id', $disease->id);
            $isFirst     = true;

            foreach ($diseaseRules as $rule) {
                $symptomId = $rule->symptom_id;

                if (!isset($answers[$symptomId])) {
                    continue;
                }

                $answerIndex  = (int) $answers[$symptomId];
                $symptomKode  = $rule->symptom->kode ?? '';

                // CF User: pemetaan jawaban ke nilai kepastian pengguna
                // Menggunakan bobot khusus untuk gejala kritis G09
                $cfUser = $this->mapAnswerToCfUser($answerIndex, $symptomKode);

                // CF Pakar: MB (Measure of Belief) dan MD (Measure of Disbelief)
                $mb = (float) $rule->mb;
                $md = (float) $rule->md;

                // CF Rule = MB - MD (nilai kepastian pakar)
                $cfRule = $mb - $md;

                // CF(H,E) = CF User × CF Rule
                $cfStep = $cfUser * $cfRule;

                // Kombinasi sekuensial CF (Rumus Kombinasi Certainty Factor)
                if ($isFirst) {
                    $cfCombine = $cfStep;
                    $isFirst   = false;
                } else {
                    $cfCombine = $this->combineCf($cfCombine, $cfStep);
                }
            }

            $allResults[$disease->id] = [
                'disease'       => $disease,
                'cf_result'     => round($cfCombine, 4),
                'cf_percentage' => round($cfCombine * 100, 1),
            ];
        }

        // --- Fase 2: Hitung total skor BDI-II ---
        foreach ($answers as $symptomId => $answerIndex) {
            $totalScore += (int) $answerIndex;
        }

        // --- Fase 3: Tentukan penyakit terbaik berdasarkan CF tertinggi ---
        $bestResult  = collect($allResults)->sortByDesc('cf_result')->first();
        $bestDisease = $bestResult['disease'] ?? $diseases->first();
        $bestCf      = $bestResult['cf_result'] ?? 0.0;
        $bestPct     = $bestResult['cf_percentage'] ?? 0.0;

        // --- Fase 4: Klasifikasi berdasarkan CF Gabungan (Tabel 3.9 Skripsi) ---
        $classification = $this->getDiagnosisFromCF($bestCf);

        // --- Fase 5: Bangun detail per gejala ---
        $flatDetails = [];
        foreach ($answers as $symptomId => $answerIndex) {
            $symptom    = $symptoms->get($symptomId);
            $symptomKode = $symptom->kode ?? '';
            $cfUser     = $this->mapAnswerToCfUser((int) $answerIndex, $symptomKode);

            $flatDetails[] = [
                'symptom_id'   => $symptomId,
                'symptom_kode' => $symptomKode,
                'symptom_nama' => $symptom->nama ?? '',
                'aspek'        => $symptom->aspek ?? '',
                'answer_index' => (int) $answerIndex,
                'answer_score' => (int) $answerIndex,
                'cf_user'      => round($cfUser, 2),
            ];
        }

        return [
            'best_disease'   => $bestDisease,
            'best_cf'        => $bestCf,
            'best_percentage' => $bestPct,
            'classification'  => $classification,
            'total_score'     => $totalScore,
            'details'         => $flatDetails,
            'all_diseases'    => $allResults,
        ];
    }

    /**
     * Memetakan answer_index (0-3) ke nilai CF User.
     *
     * Pembobotan standar mengacu pada Tabel 3.7 dokumen Skripsi:
     *   0 → 0.00 | 1 → 0.25 | 2 → 0.65 | 3 → 1.00
     *
     * PENGECUALIAN: Untuk gejala kode G09 (Pikiran untuk Bunuh Diri),
     * pembobotan mengacu pada Tabel 3.8 dokumen Skripsi:
     *   0 → 0.00 | 1 → 0.30 | 2 → 0.70 | 3 → 1.00
     *
     * Pengecekan kode gejala bersifat case-insensitive.
     *
     * @param  int    $index       Indeks jawaban pengguna (0-3)
     * @param  string $symptomKode Kode gejala (misal "G01", "G09")
     * @return float               Nilai CF User (0.00 - 1.00)
     */
    public function mapAnswerToCfUser(int $index, string $symptomKode = ''): float
    {
        // Pengecekan case-insensitive untuk gejala kritis G09
        $isCriticalG09 = strtoupper(trim($symptomKode)) === self::CRITICAL_SYMPTOM_CODE;

        if ($isCriticalG09) {
            // Pembobotan khusus G09 (Tabel 3.8 Skripsi)
            return match ($index) {
                0       => self::CF_USER_G09[0],
                1       => self::CF_USER_G09[1],
                2       => self::CF_USER_G09[2],
                3       => self::CF_USER_G09[3],
                default => 0.00,
            };
        }

        // Pembobotan standar (Tabel 3.7 Skripsi)
        return match ($index) {
            0       => self::CF_USER_STANDARD[0],
            1       => self::CF_USER_STANDARD[1],
            2       => self::CF_USER_STANDARD[2],
            3       => self::CF_USER_STANDARD[3],
            default => 0.00,
        };
    }

    /**
     * Menentukan klasifikasi diagnosis berdasarkan nilai akhir CF Gabungan.
     *
     * PENTING: Klasifikasi BUKAN berdasarkan total skor BDI-II,
     * melainkan berdasarkan nilai CF Gabungan sesuai Tabel 3.9 Skripsi:
     *
     *   CF < 0.30          → Minimal (Normal)
     *   0.30 <= CF < 0.50  → Ringan (Mild Depression)
     *   0.50 <= CF < 0.70  → Sedang (Moderate Depression)
     *   CF >= 0.70         → Berat (Severe Depression)
     *
     * @param  float  $cfGabungan Nilai akhir CF Gabungan (0.00 - 1.00)
     * @return string             Klasifikasi diagnosis
     */
    public function getDiagnosisFromCF(float $cfGabungan): string
    {
        return match (true) {
            $cfGabungan < 0.30  => 'Minimal',
            $cfGabungan < 0.50  => 'Ringan',
            $cfGabungan < 0.70  => 'Sedang',
            default             => 'Berat',
        };
    }

    /**
     * Kombinasi sekuensial dua nilai CF menggunakan rumus standar.
     *
     * Tiga skenario sesuai teori Certainty Factor:
     * 1. Kedua CF >= 0: CF = CF_old + CF_new × (1 - CF_old)
     * 2. Kedua CF <  0: CF = CF_old + CF_new × (1 + CF_old)
     * 3. Berbeda tanda: CF = (CF_old + CF_new) / (1 - min(|CF_old|, |CF_new|))
     *
     * @param  float $cfOld Nilai CF yang sudah dikombinasi sebelumnya
     * @param  float $cfNew Nilai CF baru yang akan dikombinasikan
     * @return float        Hasil kombinasi CF
     */
    private function combineCf(float $cfOld, float $cfNew): float
    {
        if ($cfOld >= 0 && $cfNew >= 0) {
            return $cfOld + $cfNew * (1 - $cfOld);
        }

        if ($cfOld < 0 && $cfNew < 0) {
            return $cfOld + $cfNew * (1 + $cfOld);
        }

        $denominator = 1 - min(abs($cfOld), abs($cfNew));

        // Hindari pembagian dengan nol
        if ($denominator == 0) {
            return 0.0;
        }

        return ($cfOld + $cfNew) / $denominator;
    }

    /**
     * Klasifikasi berdasarkan total skor BDI-II (disimpan sebagai referensi).
     *
     * Catatan: Method ini TIDAK digunakan untuk klasifikasi diagnosis utama.
     * Klasifikasi diagnosis utama menggunakan getDiagnosisFromCF().
     * Method ini hanya berfungsi sebagai referensi skor BDI-II standar.
     *
     * @param  int    $score Total skor BDI-II (0-63)
     * @return string        Klasifikasi BDI-II
     */
    public function classifyByBdiScore(int $score): string
    {
        return match (true) {
            $score <= 13 => 'Minimal',
            $score <= 19 => 'Ringan',
            $score <= 28 => 'Sedang',
            default      => 'Berat',
        };
    }
}
