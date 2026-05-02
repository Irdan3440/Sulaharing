@extends('layouts.guest')

@section('title', 'Daftar — SulaHaring')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <a href="{{ url('/') }}" class="auth-logo">
                <img src="{{ asset('images/logo-sulaharing.png') }}" alt="SulaHaring" style="width:44px;height:44px;object-fit:contain">
                <span class="auth-logo-text">Sula<span>Haring</span></span>
            </a>

            <h3 class="auth-title">Buat Akun Baru</h3>
            <p class="auth-subtitle">Daftar untuk menyimpan riwayat kesehatan mentalmu</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i data-lucide="alert-circle" style="width:18px;height:18px;flex-shrink:0"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p style="margin-bottom:2px">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" class="auth-form" id="register-form">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">
                        <i data-lucide="user" style="width:16px;height:16px"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nim" class="form-label">
                            <i data-lucide="hash" style="width:16px;height:16px"></i>
                            NIM
                        </label>
                        <input type="text" id="nim" name="nim" class="form-input" placeholder="202X0XXXX" value="{{ old('nim') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="fakultas" class="form-label">
                            <i data-lucide="building-2" style="width:16px;height:16px"></i>
                            Fakultas
                        </label>
                        <select id="fakultas" name="fakultas" class="form-input" required>
                            <option value="">Pilih Fakultas</option>
                            <option value="FKIP" {{ old('fakultas') == 'FKIP' ? 'selected' : '' }}>FKIP</option>
                            <option value="FEB" {{ old('fakultas') == 'FEB' ? 'selected' : '' }}>FEB</option>
                            <option value="FH" {{ old('fakultas') == 'FH' ? 'selected' : '' }}>FH</option>
                            <option value="FT" {{ old('fakultas') == 'FT' ? 'selected' : '' }}>FT</option>
                            <option value="FK" {{ old('fakultas') == 'FK' ? 'selected' : '' }}>FK</option>
                            <option value="FISIP" {{ old('fakultas') == 'FISIP' ? 'selected' : '' }}>FISIP</option>
                            <option value="FMIPA" {{ old('fakultas') == 'FMIPA' ? 'selected' : '' }}>FMIPA</option>
                            <option value="FPP" {{ old('fakultas') == 'FPP' ? 'selected' : '' }}>FPP</option>
                            <option value="Lainnya" {{ old('fakultas') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i data-lucide="mail" style="width:16px;height:16px"></i>
                        Email
                    </label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="nama@universitas.ac.id" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i data-lucide="lock" style="width:16px;height:16px"></i>
                        Password
                    </label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'eye1')" aria-label="Toggle password">
                            <i data-lucide="eye" id="eye1" style="width:18px;height:18px"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i data-lucide="shield-check" style="width:16px;height:16px"></i>
                        Konfirmasi Password
                    </label>
                    <div class="password-field">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'eye2')" aria-label="Toggle password">
                            <i data-lucide="eye" id="eye2" style="width:18px;height:18px"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-register">
                    <i data-lucide="user-plus" style="width:20px;height:20px"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ url('/login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}
</script>
@endpush
