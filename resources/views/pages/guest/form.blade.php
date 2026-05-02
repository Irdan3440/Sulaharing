@extends('layouts.guest')

@section('title', 'Mulai Diagnosa — SulaHaring')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/questionnaire.css') }}">
@endpush

@section('content')
<div class="questionnaire-page">
    <div class="questionnaire-container guest-form-card">

        <!-- Back button -->
        <div style="margin-bottom: var(--space-6);">
            <a href="{{ url('/') }}" class="btn btn-secondary btn-sm" style="text-decoration:none">
                <i data-lucide="arrow-left" style="width:16px;height:16px"></i>
                Kembali
            </a>
        </div>

        <div class="question-card" style="text-align:center;">
            <a href="{{ url('/') }}" class="auth-logo" style="display:inline-flex; margin-bottom:var(--space-4);">
                <img src="{{ asset('images/logo-sulaharing.png') }}" alt="SulaHaring" style="width:44px;height:44px;object-fit:contain">
            </a>

            <h2 style="margin-bottom:var(--space-2);">Konsultasi Kesehatan Mental</h2>
            <p style="font-size:var(--text-sm); color:var(--text-secondary); margin-bottom:var(--space-8);">
                Evaluasi kesehatan mental anda dengan sistem diagnosa berbasis kecerdasan buatan
            </p>

            <div style="text-align:left;">
                <form method="POST" action="{{ url('/diagnosa/process') }}" id="guest-form">
                    @csrf

                    <div class="form-group">
                        <label for="guest_name" class="form-label">
                            <i data-lucide="user" style="width:16px;height:16px"></i>
                            Nama Panggilan
                        </label>
                        <input type="text" id="guest_name" name="guest_name" class="form-input" placeholder="Masukkan nama panggilan Anda" value="{{ old('guest_name') }}" required>
                        <span class="form-hint">Nama ini akan ditampilkan pada hasil diagnosis</span>
                    </div>

                    <div class="form-group">
                        <label for="guest_institusi" class="form-label">
                            <i data-lucide="building-2" style="width:16px;height:16px"></i>
                            Fakultas / Institusi
                        </label>
                        <input type="text" id="guest_institusi" name="guest_institusi" class="form-input" placeholder="Contoh: FKIP / Universitas XYZ" value="{{ old('guest_institusi') }}">
                    </div>

                    <div class="form-group">
                        <label for="guest_usia" class="form-label">
                            <i data-lucide="calendar" style="width:16px;height:16px"></i>
                            Usia
                        </label>
                        <input type="number" id="guest_usia" name="guest_usia" class="form-input" placeholder="Usia Anda" min="15" max="99" value="{{ old('guest_usia') }}">
                    </div>

                    <div class="alert alert-info" style="margin-top:var(--space-6)">
                        <i data-lucide="info" style="width:18px;height:18px;flex-shrink:0"></i>
                        <span>Sebagai tamu, hasil diagnosa Anda <strong>tidak akan disimpan</strong> oleh sistem. Untuk menyimpan riwayat, silakan <a href="{{ url('/register') }}" style="font-weight:700">buat akun</a>.</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:var(--space-4)" id="btn-mulai-kuesioner">
                        <i data-lucide="clipboard-check" style="width:20px;height:20px"></i>
                        Mulai Kuesioner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
