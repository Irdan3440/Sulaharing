@extends('layouts.guest')

@section('title', 'Masuk — SulaHaring')

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

            <h3 class="auth-title">Selamat Datang Kembali</h3>
            <p class="auth-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i data-lucide="alert-circle" style="width:18px;height:18px;flex-shrink:0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="auth-form" id="login-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i data-lucide="mail" style="width:16px;height:16px"></i>
                        Email
                    </label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="nama@universitas.ac.id" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i data-lucide="lock" style="width:16px;height:16px"></i>
                        Password
                    </label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="eye-icon" style="width:18px;height:18px"></i>
                        </button>
                    </div>
                </div>

                <div class="auth-extras">
                    <label class="checkbox-field">
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-login">
                    <i data-lucide="log-in" style="width:20px;height:20px"></i>
                    Masuk
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ url('/register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
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
