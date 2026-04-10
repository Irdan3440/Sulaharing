<?php

namespace App\Http\Controllers;

use App\Models\ConsultationResult;
use App\Models\MoodEntry;
use App\Models\BiometricData;
use App\Models\IotAlert;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $totalConsultations = ConsultationResult::where('user_id', $user->id)->count();
        $lastConsultation = ConsultationResult::where('user_id', $user->id)->latest('consulted_at')->first();
        $lastCf = $lastConsultation ? $lastConsultation->cf_percentage : 0;
        $moodStreak = $user->mood_streak;
        $latestBio = BiometricData::where('user_id', $user->id)->latest('recorded_at')->first();
        $currentBpm = $latestBio->heart_rate ?? '--';

        // Mood data for chart (last 7 days)
        $moodData = MoodEntry::where('user_id', $user->id)
            ->where('entry_date', '>=', now()->subDays(7))
            ->orderBy('entry_date')
            ->get();

        // Recent consultations
        $recentConsultations = ConsultationResult::where('user_id', $user->id)
            ->latest('consulted_at')
            ->take(5)
            ->get();

        // Unread alerts
        $unreadAlerts = IotAlert::where('user_id', $user->id)->where('is_read', false)->count();

        return view('pages.mahasiswa.dashboard', compact(
            'totalConsultations', 'lastCf', 'moodStreak', 'currentBpm',
            'moodData', 'recentConsultations', 'latestBio', 'unreadAlerts'
        ));
    }

    public function riwayat()
    {
        $consultations = ConsultationResult::where('user_id', auth()->id())
            ->with('disease')
            ->latest('consulted_at')
            ->paginate(10);

        return view('pages.mahasiswa.riwayat', compact('consultations'));
    }

    public function riwayatDetail($id)
    {
        $consultation = ConsultationResult::where('user_id', auth()->id())
            ->with(['details.symptom', 'disease'])
            ->findOrFail($id);

        return view('pages.mahasiswa.riwayat-detail', compact('consultation'));
    }

    public function biometric()
    {
        $data = BiometricData::where('user_id', auth()->id())
            ->latest('recorded_at')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $latest = $data->last();

        return view('pages.mahasiswa.biometric', compact('data', 'latest'));
    }

    public function notifications()
    {
        $alerts = IotAlert::where('user_id', auth()->id())
            ->latest('alerted_at')
            ->paginate(15);

        // Mark all as read
        IotAlert::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);

        return view('pages.mahasiswa.notifications', compact('alerts'));
    }

    public function storeMood(Request $request)
    {
        $request->validate(['mood_score' => 'required|integer|min:1|max:5']);

        MoodEntry::updateOrCreate(
            ['user_id' => auth()->id(), 'entry_date' => now()->toDateString()],
            ['mood_score' => $request->mood_score, 'catatan' => $request->catatan]
        );

        return back()->with('success', 'Mood hari ini tersimpan! 🎉');
    }
}
