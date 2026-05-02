{{-- Sidebar Navigation — shared across all mahasiswa pages --}}
@php
    $currentRoute = request()->path();
@endphp

<div class="sidebar-section">
    <div class="sidebar-section-title">Menu Utama</div>
    <ul>
        <li><a href="{{ url('/dashboard') }}" class="sidebar-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i> Dashboard
        </a></li>
        <li><a href="{{ url('/konsultasi') }}" class="sidebar-link {{ $currentRoute === 'konsultasi' ? 'active' : '' }}">
            <i data-lucide="stethoscope"></i> Mulai Konsultasi
        </a></li>
        <li><a href="{{ url('/riwayat') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'riwayat') ? 'active' : '' }}">
            <i data-lucide="history"></i> Riwayat
        </a></li>
        <li><a href="{{ url('/biometric') }}" class="sidebar-link {{ $currentRoute === 'biometric' ? 'active' : '' }}">
            <i data-lucide="heart-pulse"></i> Biometrik
        </a></li>
    </ul>
</div>
<div class="sidebar-section">
    <div class="sidebar-section-title">Lainnya</div>
    <ul>
        <li><a href="{{ url('/notifications') }}" class="sidebar-link {{ $currentRoute === 'notifications' ? 'active' : '' }}">
            <i data-lucide="bell"></i> Notifikasi
        </a></li>
        <li><a href="{{ url('/pengaturan') }}" class="sidebar-link {{ $currentRoute === 'pengaturan' ? 'active' : '' }}">
            <i data-lucide="settings"></i> Pengaturan
        </a></li>
    </ul>
</div>
