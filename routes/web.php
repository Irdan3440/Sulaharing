<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PakarController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| SulaHaring — Web Routes
|--------------------------------------------------------------------------
*/

// === Public / Landing ===
Route::get('/', function () { return view('pages.home'); })->name('home');

// === Auth ===
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === Guest Diagnosis ===
Route::get('/diagnosa', [ConsultationController::class, 'guestForm'])->name('guest.form');
Route::post('/diagnosa/process', [ConsultationController::class, 'guestStart'])->name('guest.process');
Route::post('/diagnosa/result', [ConsultationController::class, 'guestResult'])->name('guest.result');
Route::get('/diagnosa/result/{id}/pdf', [ConsultationController::class, 'guestPdf'])->name('guest.pdf');

// === Mahasiswa (authenticated) ===
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/konsultasi', [ConsultationController::class, 'mahasiswaStart'])->name('konsultasi');
    Route::post('/konsultasi/result', [ConsultationController::class, 'mahasiswaResult'])->name('konsultasi.result');
    Route::get('/riwayat', [MahasiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/{id}', [MahasiswaController::class, 'riwayatDetail'])->name('riwayat.detail');
    Route::get('/riwayat/{id}/pdf', [MahasiswaController::class, 'downloadPdf'])->name('riwayat.pdf');
    Route::get('/biometric', [MahasiswaController::class, 'biometric'])->name('biometric');
    Route::get('/notifications', [MahasiswaController::class, 'notifications'])->name('notifications');
    Route::post('/mood', [MahasiswaController::class, 'storeMood'])->name('mood.store');
});

// === Pakar ===
Route::middleware(['auth', 'role:pakar'])->prefix('pakar')->name('pakar.')->group(function () {
    Route::get('/dashboard', [PakarController::class, 'dashboard'])->name('dashboard');
    Route::get('/diseases', [PakarController::class, 'diseases'])->name('diseases');
    Route::post('/diseases', [PakarController::class, 'diseaseStore'])->name('diseases.store');
    Route::put('/diseases/{disease}', [PakarController::class, 'diseaseUpdate'])->name('diseases.update');
    Route::delete('/diseases/{disease}', [PakarController::class, 'diseaseDestroy'])->name('diseases.destroy');
    Route::get('/symptoms', [PakarController::class, 'symptoms'])->name('symptoms');
    Route::post('/symptoms', [PakarController::class, 'symptomStore'])->name('symptoms.store');
    Route::put('/symptoms/{symptom}', [PakarController::class, 'symptomUpdate'])->name('symptoms.update');
    Route::delete('/symptoms/{symptom}', [PakarController::class, 'symptomDestroy'])->name('symptoms.destroy');
    Route::get('/rules', [PakarController::class, 'rules'])->name('rules');
    Route::post('/rules', [PakarController::class, 'ruleStore'])->name('rules.store');
    Route::put('/rules/{rule}', [PakarController::class, 'ruleUpdate'])->name('rules.update');
});

// === Admin ===
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'userDestroy'])->name('users.destroy');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
