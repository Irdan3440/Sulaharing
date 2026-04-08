@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali, ' . (auth()->user()->name ?? 'User'))

@section('sidebar')
<div class="sidebar-section">
    <div class="sidebar-section-title">Menu Utama</div>
    <ul>
        <li><a href="{{ url('/dashboard') }}" class="sidebar-link active">
            <i data-lucide="layout-dashboard"></i> Dashboard
        </a></li>
        <li><a href="{{ url('/konsultasi') }}" class="sidebar-link">
            <i data-lucide="stethoscope"></i> Mulai Konsultasi
        </a></li>
        <li><a href="{{ url('/riwayat') }}" class="sidebar-link">
            <i data-lucide="history"></i> Riwayat
        </a></li>
        <li><a href="{{ url('/biometric') }}" class="sidebar-link">
            <i data-lucide="heart-pulse"></i> Biometrik
        </a></li>
    </ul>
</div>
<div class="sidebar-section">
    <div class="sidebar-section-title">Lainnya</div>
    <ul>
        <li><a href="{{ url('/notifications') }}" class="sidebar-link">
            <i data-lucide="bell"></i> Notifikasi
            <span class="badge-count">3</span>
        </a></li>
        <li><a href="#" class="sidebar-link">
            <i data-lucide="settings"></i> Pengaturan
        </a></li>
    </ul>
</div>
@endsection

@push('styles')
<style>
    .welcome-banner {
        background: var(--gradient-primary);
        border-radius: var(--radius-2xl);
        padding: var(--space-8);
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: var(--space-6);
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
    }
    .welcome-banner h3 { color: white; margin-bottom: var(--space-2); }
    .welcome-banner p { color: rgba(255,255,255,0.8); font-size: var(--text-sm); }
    .welcome-date { 
        font-size: var(--text-xs); 
        color: rgba(255,255,255,0.6);
        margin-top: var(--space-3);
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner animate-fade-in-down">
    <h3>Hai, {{ auth()->user()->name ?? 'User' }}! 👋</h3>
    <p>Bagaimana perasaanmu hari ini? Jangan lupa isi mood tracker harianmu.</p>
    <div class="welcome-date">
        <i data-lucide="calendar" style="width:14px;height:14px;vertical-align:middle"></i>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card animate-fade-in-up">
        <div class="stat-icon blue">
            <i data-lucide="clipboard-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Konsultasi</div>
            <div class="stat-value">{{ $total_consultations ?? 5 }}</div>
            <div class="stat-change up">
                <i data-lucide="trending-up" style="width:14px;height:14px"></i>
                +2 bulan ini
            </div>
        </div>
    </div>

    <div class="stat-card animate-fade-in-up delay-100">
        <div class="stat-icon green">
            <i data-lucide="brain"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Skor CF Terakhir</div>
            <div class="stat-value">{{ $last_cf ?? '35' }}%</div>
            <div class="stat-change up">
                <i data-lucide="trending-up" style="width:14px;height:14px"></i>
                Membaik
            </div>
        </div>
    </div>

    <div class="stat-card animate-fade-in-up delay-200">
        <div class="stat-icon violet">
            <i data-lucide="flame"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Streak Mood</div>
            <div class="stat-value">{{ $mood_streak ?? 7 }}</div>
            <div class="stat-change up">
                <i data-lucide="zap" style="width:14px;height:14px"></i>
                hari berturut-turut
            </div>
        </div>
    </div>

    <div class="stat-card animate-fade-in-up delay-300">
        <div class="stat-icon cyan">
            <i data-lucide="heart-pulse"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">BPM Saat Ini</div>
            <div class="stat-value">{{ $current_bpm ?? '72' }}</div>
            <div class="stat-change up">
                <i data-lucide="check-circle" style="width:14px;height:14px"></i>
                Normal
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ url('/konsultasi') }}" class="quick-action-card animate-fade-in-up">
        <div class="quick-action-icon" style="background:var(--gradient-primary);">
            <i data-lucide="stethoscope" style="color:white;width:22px;height:22px"></i>
        </div>
        <div class="quick-action-info">
            <h5>Mulai Konsultasi</h5>
            <p>Isi kuesioner BDI-II untuk diagnosis baru</p>
        </div>
    </a>

    <a href="{{ url('/riwayat') }}" class="quick-action-card animate-fade-in-up delay-100">
        <div class="quick-action-icon" style="background:var(--gradient-violet);">
            <i data-lucide="history" style="color:white;width:22px;height:22px"></i>
        </div>
        <div class="quick-action-info">
            <h5>Lihat Riwayat</h5>
            <p>Evaluasi progres kesehatan mentalmu</p>
        </div>
    </a>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Chart: Mood History -->
    <div class="chart-card animate-fade-in-up delay-200">
        <div class="chart-header">
            <h5 class="chart-title">
                <i data-lucide="trending-up" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i>
                Grafik Mood Bulanan
            </h5>
            <div class="chart-actions">
                <button class="chart-period-btn active">Minggu</button>
                <button class="chart-period-btn">Bulan</button>
                <button class="chart-period-btn">Tahun</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="moodChart"></canvas>
        </div>
    </div>

    <!-- Mood Tracker Widget -->
    <div>
        <div class="mood-widget animate-fade-in-up delay-300">
            <h5 style="font-size:var(--text-base);font-weight:700;margin-bottom:var(--space-4);text-align:center;">
                <i data-lucide="smile" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i>
                Mood Hari Ini
            </h5>
            <div class="mood-today">
                <p class="mood-question">Bagaimana perasaanmu hari ini?</p>
                <div class="mood-emojis">
                    <button class="mood-emoji" data-mood="1" title="Sangat Buruk">😢</button>
                    <button class="mood-emoji" data-mood="2" title="Buruk">😔</button>
                    <button class="mood-emoji" data-mood="3" title="Biasa">😐</button>
                    <button class="mood-emoji" data-mood="4" title="Baik">😊</button>
                    <button class="mood-emoji" data-mood="5" title="Sangat Baik">😄</button>
                </div>
            </div>
            <div class="mood-streak">
                🔥 Streak: <strong>7 hari</strong> berturut-turut!
            </div>
        </div>

        <!-- Biometric Status -->
        <div class="biometric-panel animate-fade-in-up delay-400" style="margin-top:var(--space-5)">
            <h5 style="font-size:var(--text-base);font-weight:700;margin-bottom:var(--space-3);">
                <i data-lucide="watch" style="width:18px;height:18px;color:var(--accent-500);vertical-align:middle"></i>
                Status Smartwatch
            </h5>
            <div class="biometric-status">
                <span class="status-indicator online"></span>
                <span style="font-size:var(--text-sm);color:var(--success-600);font-weight:600">Terhubung</span>
            </div>
            <div class="biometric-metrics">
                <div class="biometric-metric">
                    <div class="biometric-metric-value">72</div>
                    <div class="biometric-metric-label">BPM</div>
                </div>
                <div class="biometric-metric">
                    <div class="biometric-metric-value">45</div>
                    <div class="biometric-metric-label">HRV <span class="biometric-metric-unit">ms</span></div>
                </div>
                <div class="biometric-metric">
                    <div class="biometric-metric-value">98</div>
                    <div class="biometric-metric-label">SpO2 <span class="biometric-metric-unit">%</span></div>
                </div>
                <div class="biometric-metric">
                    <div class="biometric-metric-value">Normal</div>
                    <div class="biometric-metric-label">Status</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent History -->
<div class="chart-card animate-fade-in-up delay-400" style="margin-top:var(--space-6)">
    <div class="chart-header">
        <h5 class="chart-title">
            <i data-lucide="clock" style="width:20px;height:20px;color:var(--violet-500);vertical-align:middle"></i>
            Riwayat Konsultasi Terakhir
        </h5>
        <a href="{{ url('/riwayat') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>

    <div class="recent-list">
        <a href="#" class="recent-item">
            <div class="recent-item-date">
                <span class="recent-item-day">05</span>
                <span class="recent-item-month">Apr</span>
            </div>
            <div class="recent-item-info">
                <div class="recent-item-title">Konsultasi #5</div>
                <div class="recent-item-subtitle">Depresi Ringan — Skor BDI: 16</div>
            </div>
            <div class="recent-item-score ringan">35%</div>
        </a>

        <a href="#" class="recent-item">
            <div class="recent-item-date">
                <span class="recent-item-day">20</span>
                <span class="recent-item-month">Mar</span>
            </div>
            <div class="recent-item-info">
                <div class="recent-item-title">Konsultasi #4</div>
                <div class="recent-item-subtitle">Depresi Minimal — Skor BDI: 10</div>
            </div>
            <div class="recent-item-score minimal">22%</div>
        </a>

        <a href="#" class="recent-item">
            <div class="recent-item-date">
                <span class="recent-item-day">05</span>
                <span class="recent-item-month">Mar</span>
            </div>
            <div class="recent-item-info">
                <div class="recent-item-title">Konsultasi #3</div>
                <div class="recent-item-subtitle">Depresi Sedang — Skor BDI: 24</div>
            </div>
            <div class="recent-item-score sedang">58%</div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    // Mood emoji selection
    document.querySelectorAll('.mood-emoji').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.mood-emoji').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // Mood Chart
    const ctx = document.getElementById('moodChart');
    if (ctx) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Mood Score',
                    data: [3, 4, 3, 5, 4, 4, 5],
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#3b82f6',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e3a5f',
                        titleFont: { family: 'Inter', weight: '600' },
                        bodyFont: { family: 'Inter' },
                        cornerRadius: 8,
                        padding: 12,
                    }
                },
                scales: {
                    y: {
                        min: 0, max: 5,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#94a3b8',
                            callback: function(value) {
                                const emojis = ['', '😢', '😔', '😐', '😊', '😄'];
                                return emojis[value] || value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8' }
                    }
                }
            }
        });
    }
</script>
@endpush
