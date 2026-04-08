<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SulaHaring — Web Routes
|--------------------------------------------------------------------------
*/

// === Public / Landing ===
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// === Auth ===
Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::post('/login', function () {
    // TODO: Implement auth logic
    return redirect('/dashboard');
});

Route::get('/register', function () {
    return view('pages.auth.register');
})->name('register');

Route::post('/register', function () {
    // TODO: Implement registration logic
    return redirect('/login');
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// === Guest Diagnosis ===
Route::get('/diagnosa', function () {
    return view('pages.guest.form');
})->name('guest.form');

Route::post('/diagnosa/process', function (\Illuminate\Http\Request $request) {
    return view('pages.guest.questionnaire', [
        'guest_name' => $request->input('guest_name', ''),
        'guest_institusi' => $request->input('guest_institusi', ''),
        'guest_usia' => $request->input('guest_usia', ''),
    ]);
})->name('guest.process');

Route::post('/diagnosa/result', function (\Illuminate\Http\Request $request) {
    // Collect answers and calculate CF score (placeholder)
    $totalScore = 0;
    for ($i = 1; $i <= 21; $i++) {
        $totalScore += (int) $request->input('answer_' . $i, 0);
    }

    // Simple CF calculation placeholder
    $cfPercentage = round(($totalScore / 63) * 100, 1);

    // Classify
    if ($totalScore <= 13) {
        $classification = 'Minimal';
    } elseif ($totalScore <= 19) {
        $classification = 'Ringan';
    } elseif ($totalScore <= 28) {
        $classification = 'Sedang';
    } else {
        $classification = 'Berat';
    }

    return view('pages.guest.result', [
        'cf_percentage' => $cfPercentage,
        'classification' => $classification,
        'raw_score' => $totalScore,
        'is_guest' => true,
        'guest_name' => $request->input('guest_name', ''),
    ]);
})->name('guest.result');

// === Mahasiswa Dashboard (placeholder — works without auth for preview) ===
Route::get('/dashboard', function () {
    return view('pages.mahasiswa.dashboard');
})->name('dashboard');

// Placeholder routes for sidebar links
Route::get('/konsultasi', function () {
    return view('pages.guest.questionnaire', [
        'guest_name' => auth()->user()->name ?? 'User',
        'guest_institusi' => '',
        'guest_usia' => '',
    ]);
})->name('konsultasi');

Route::get('/riwayat', function () {
    return redirect('/dashboard');
})->name('riwayat');

Route::get('/biometric', function () {
    return redirect('/dashboard');
})->name('biometric');

Route::get('/notifications', function () {
    return redirect('/dashboard');
})->name('notifications');
