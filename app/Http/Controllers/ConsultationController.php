<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use App\Models\ConsultationResult;
use App\Models\ConsultationDetail;
use App\Services\CertaintyFactorEngine;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function guestForm()
    {
        return view('pages.guest.form');
    }

    public function guestStart(Request $request)
    {
        $request->validate(['guest_name' => 'required|string|max:100']);

        $symptoms = Symptom::orderBy('kode')->get();

        return view('pages.guest.questionnaire', [
            'symptoms' => $symptoms,
            'guest_name' => $request->input('guest_name'),
            'guest_institusi' => $request->input('guest_institusi', ''),
            'guest_usia' => $request->input('guest_usia', ''),
        ]);
    }

    public function guestResult(Request $request)
    {
        $symptoms = Symptom::orderBy('kode')->get();

        // Collect answers: symptom_id => answer_index
        $answers = [];
        foreach ($symptoms as $symptom) {
            $key = 'answer_' . $symptom->id;
            $answers[$symptom->id] = (int) $request->input($key, 0);
        }

        // Calculate CF
        $engine = new CertaintyFactorEngine();
        $result = $engine->calculate($answers);

        // Save to DB (as guest)
        $consultation = ConsultationResult::create([
            'user_id' => null,
            'disease_id' => $result['best_disease']->id ?? null,
            'guest_name' => $request->input('guest_name', 'Tamu'),
            'guest_institusi' => $request->input('guest_institusi'),
            'guest_usia' => $request->input('guest_usia'),
            'is_guest' => true,
            'total_score_bdi' => $result['total_score'],
            'cf_result' => $result['best_cf'],
            'cf_percentage' => $result['best_percentage'],
            'classification' => $result['classification'],
            'cf_detail' => $result['details'],
            'saran' => $result['best_disease']->saran_penanganan ?? '',
            'consulted_at' => now(),
        ]);

        // Save per-symptom details
        foreach ($result['details'] as $detail) {
            ConsultationDetail::create([
                'consultation_result_id' => $consultation->id,
                'symptom_id' => $detail['symptom_id'],
                'answer_index' => $detail['answer_index'],
                'answer_score' => $detail['answer_score'],
                'cf_user' => $detail['cf_user'],
            ]);
        }

        return view('pages.guest.result', [
            'consultation' => $consultation,
            'cf_percentage' => $result['best_percentage'],
            'classification' => $result['classification'],
            'disease' => $result['best_disease'],
            'raw_score' => $result['total_score'],
            'is_guest' => true,
            'details' => $result['details'],
        ]);
    }

    // === Mahasiswa (Authenticated) ===
    public function mahasiswaStart()
    {
        $symptoms = Symptom::orderBy('kode')->get();

        return view('pages.guest.questionnaire', [
            'symptoms' => $symptoms,
            'guest_name' => auth()->user()->name,
            'guest_institusi' => auth()->user()->fakultas,
            'guest_usia' => '',
        ]);
    }

    public function mahasiswaResult(Request $request)
    {
        $symptoms = Symptom::orderBy('kode')->get();
        $answers = [];
        foreach ($symptoms as $symptom) {
            $answers[$symptom->id] = (int) $request->input('answer_' . $symptom->id, 0);
        }

        $engine = new CertaintyFactorEngine();
        $result = $engine->calculate($answers);

        $consultation = ConsultationResult::create([
            'user_id' => auth()->id(),
            'disease_id' => $result['best_disease']->id ?? null,
            'is_guest' => false,
            'total_score_bdi' => $result['total_score'],
            'cf_result' => $result['best_cf'],
            'cf_percentage' => $result['best_percentage'],
            'classification' => $result['classification'],
            'cf_detail' => $result['details'],
            'saran' => $result['best_disease']->saran_penanganan ?? '',
            'consulted_at' => now(),
        ]);

        foreach ($result['details'] as $detail) {
            ConsultationDetail::create([
                'consultation_result_id' => $consultation->id,
                'symptom_id' => $detail['symptom_id'],
                'answer_index' => $detail['answer_index'],
                'answer_score' => $detail['answer_score'],
                'cf_user' => $detail['cf_user'],
            ]);
        }

        return view('pages.guest.result', [
            'consultation' => $consultation,
            'cf_percentage' => $result['best_percentage'],
            'classification' => $result['classification'],
            'disease' => $result['best_disease'],
            'raw_score' => $result['total_score'],
            'is_guest' => false,
            'details' => $result['details'],
        ]);
    }
}
