<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Symptom;
use App\Models\Rule;
use App\Models\ConsultationResult;
use App\Models\User;
use Illuminate\Http\Request;

class PakarController extends Controller
{
    public function dashboard()
    {
        $totalDiseases = Disease::count();
        $totalSymptoms = Symptom::count();
        $totalRules = Rule::count();
        $totalConsultations = ConsultationResult::count();

        $recentConsultations = ConsultationResult::with('user', 'disease')
            ->latest('consulted_at')->take(5)->get();

        $highRiskStudents = ConsultationResult::where('cf_percentage', '>=', 60)
            ->whereNotNull('user_id')
            ->with('user', 'disease')
            ->latest('consulted_at')->take(10)->get();

        // Stats per classification
        $classStats = ConsultationResult::selectRaw("classification, COUNT(*) as total")
            ->groupBy('classification')->pluck('total', 'classification');

        return view('pages.pakar.dashboard', compact(
            'totalDiseases', 'totalSymptoms', 'totalRules', 'totalConsultations',
            'recentConsultations', 'highRiskStudents', 'classStats'
        ));
    }

    // === Diseases CRUD ===
    public function diseases() { return view('pages.pakar.diseases', ['diseases' => Disease::all()]); }

    public function diseaseStore(Request $request)
    {
        $v = $request->validate(['kode' => 'required|unique:diseases', 'nama' => 'required', 'deskripsi' => 'required', 'rentang_skor' => 'required', 'saran_penanganan' => 'nullable', 'rujukan_helpdesk' => 'nullable']);
        Disease::create(array_merge($v, ['created_by' => auth()->id()]));
        return back()->with('success', 'Penyakit berhasil ditambahkan.');
    }

    public function diseaseUpdate(Request $request, Disease $disease)
    {
        $v = $request->validate(['nama' => 'required', 'deskripsi' => 'required', 'rentang_skor' => 'required', 'saran_penanganan' => 'nullable', 'rujukan_helpdesk' => 'nullable']);
        $disease->update($v);
        return back()->with('success', 'Penyakit berhasil diperbarui.');
    }

    public function diseaseDestroy(Disease $disease)
    {
        $disease->delete();
        return back()->with('success', 'Penyakit berhasil dihapus.');
    }

    // === Symptoms CRUD ===
    public function symptoms() { return view('pages.pakar.symptoms', ['symptoms' => Symptom::orderBy('kode')->get()]); }

    public function symptomStore(Request $request)
    {
        $v = $request->validate(['kode' => 'required|unique:symptoms', 'nama' => 'required', 'deskripsi' => 'required', 'aspek' => 'required|in:kognitif,afektif,somatik', 'pilihan_jawaban' => 'required|array|min:4']);
        Symptom::create(array_merge($v, ['created_by' => auth()->id()]));
        return back()->with('success', 'Gejala berhasil ditambahkan.');
    }

    public function symptomUpdate(Request $request, Symptom $symptom)
    {
        $v = $request->validate(['nama' => 'required', 'deskripsi' => 'required', 'aspek' => 'required|in:kognitif,afektif,somatik', 'pilihan_jawaban' => 'required|array|min:4']);
        $symptom->update($v);
        return back()->with('success', 'Gejala berhasil diperbarui.');
    }

    public function symptomDestroy(Symptom $symptom)
    {
        $symptom->delete();
        return back()->with('success', 'Gejala berhasil dihapus.');
    }

    // === Rules ===
    public function rules()
    {
        $rules = Rule::with('symptom', 'disease')->orderBy('symptom_id')->get();
        $symptoms = Symptom::orderBy('kode')->get();
        $diseases = Disease::all();
        return view('pages.pakar.rules', compact('rules', 'symptoms', 'diseases'));
    }

    public function ruleUpdate(Request $request, Rule $rule)
    {
        $v = $request->validate(['mb' => 'required|numeric|min:0|max:1', 'md' => 'required|numeric|min:0|max:1']);
        $rule->update(array_merge($v, ['updated_by' => auth()->id()]));
        return back()->with('success', 'Aturan CF berhasil diperbarui.');
    }

    public function ruleStore(Request $request)
    {
        $v = $request->validate(['symptom_id' => 'required|exists:symptoms,id', 'disease_id' => 'required|exists:diseases,id', 'mb' => 'required|numeric|min:0|max:1', 'md' => 'required|numeric|min:0|max:1']);
        Rule::updateOrCreate(
            ['symptom_id' => $v['symptom_id'], 'disease_id' => $v['disease_id']],
            ['mb' => $v['mb'], 'md' => $v['md'], 'updated_by' => auth()->id()]
        );
        return back()->with('success', 'Aturan CF berhasil disimpan.');
    }
}
