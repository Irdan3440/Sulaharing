@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali, ' . (auth()->user()->name ?? 'User'))

@section('sidebar')
@include('partials.sidebar-mahasiswa')
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
<div class="quick-actions grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ url('/konsultasi') }}" class="quick-action-card animate-fade-in-up">
        <div class="quick-action-icon" style="background:var(--gradient-primary);">
            <i data-lucide="stethoscope" style="color:white;width:22px;height:22px"></i>
        </div>
        <div class="quick-action-info">
            <h5>Mulai Konsultasi</h5>
            <p>Isi kuesioner BDI-II</p>
        </div>
    </a>

    <a href="{{ url('/terapi') }}" class="quick-action-card animate-fade-in-up delay-100">
        <div class="quick-action-icon" style="background:var(--gradient-primary);">
            <i data-lucide="heart-pulse" style="color:white;width:22px;height:22px"></i>
        </div>
        <div class="quick-action-info">
            <h5>Terapi CBT</h5>
            <p>Pernapasan & Pomodoro</p>
        </div>
    </a>

    <a href="{{ url('/pengaturan/kontak-darurat') }}" class="quick-action-card animate-fade-in-up delay-200">
        <div class="quick-action-icon" style="background:var(--gradient-primary);">
            <i data-lucide="user-check" style="color:white;width:22px;height:22px"></i>
        </div>
        <div class="quick-action-info">
            <h5>Kontak Darurat</h5>
            <p>Set nomor darurat</p>
        </div>
    </a>

    <a href="{{ url('/tele-counseling') }}" class="quick-action-card animate-fade-in-up delay-300">
        <div class="quick-action-icon" style="background:var(--gradient-primary);">
            <i data-lucide="phone-outgoing" style="color:white;width:22px;height:22px"></i>
        </div>
        <div class="quick-action-info">
            <h5>Tele-Counseling</h5>
            <p>Hubungi psikolog via WA</p>
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
                <span class="status-indicator {{ $latestBio ? 'online' : 'offline' }}"></span>
                <span style="font-size:var(--text-sm);color:var(--success-600);font-weight:600">{{ $latestBio ? 'Terhubung (Aktif)' : 'Belum Ada Data' }}</span>
            </div>
            <div class="biometric-metrics">
                <div class="biometric-metric">
                    <div class="biometric-metric-value" id="val-bpm">{{ $latestBio->heart_rate ?? '--' }}</div>
                    <div class="biometric-metric-label">BPM</div>
                </div>
                <div class="biometric-metric">
                    <div class="biometric-metric-value" id="val-hrv">{{ $latestBio->hrv ?? '--' }}</div>
                    <div class="biometric-metric-label">HRV <span class="biometric-metric-unit">ms</span></div>
                </div>
                <div class="biometric-metric">
                    <div class="biometric-metric-value" id="val-spo2">{{ $latestBio->spo2 ?? '--' }}</div>
                    <div class="biometric-metric-label">SpO2 <span class="biometric-metric-unit">%</span></div>
                </div>
                <div class="biometric-metric">
                    <div class="biometric-metric-value" id="val-status" style="color:{{ ($latestBio && ($latestBio->heart_rate > 100 || $latestBio->hrv < 30)) ? 'var(--danger-500)' : 'var(--success-500)' }}">{{ $latestBio ? (($latestBio->heart_rate > 100 || $latestBio->hrv < 30) ? 'Bahaya' : 'Normal') : '--' }}</div>
                    <div class="biometric-metric-label">Status</div>
                </div>
            </div>

            <div id="wa-button-container" style="display: {{ ($latestBio && ($latestBio->heart_rate > 100 || $latestBio->hrv < 30)) ? 'block' : 'none' }};">
                @if($emergencyContact)
                    @php
                        $msg = "🚨 *SULAHARING EMERGENCY ALERT* 🚨\n\n";
                        $msg .= "Halo {$emergencyContact->name},\n\n";
                        $msg .= "Sistem mendeteksi detak jantung yang anomali/terindikasi *Panic Attack* pada:\n";
                        $msg .= "👤 *Nama:* ".auth()->user()->name."\n";
                        $msg .= "⏳ *Waktu:* (Waktu Deteksi: " . now()->format('d M Y, H:i') . ")\n\n";
                        $msg .= "Harap segera periksa kondisinya. Terima kasih.";
                        
                        $phone = preg_replace('/\D/', '', $emergencyContact->phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                        
                        $waLink = "https://wa.me/{$phone}?text=" . urlencode($msg);
                    @endphp
                    <div style="margin-top: 1.5rem;">
                        <a href="{{ $waLink }}" target="_blank" style="display:flex; justify-content:center; align-items:center; gap:8px; width:100%; background:var(--danger-500); color:white; padding:12px; border-radius:var(--radius-lg); font-weight:700; text-decoration:none; animation: pulse 2s infinite;">
                            <i data-lucide="alert-triangle" style="width:20px;height:20px;"></i>
                            KIRIM PERINGATAN WA SEKARANG
                        </a>
                    </div>
                    <style>
                        @keyframes pulse {
                            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
                            70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
                            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
                        }
                    </style>
                @else
                    <div style="margin-top: 1.5rem; background:rgba(239, 68, 68, 0.1); padding:1rem; border-radius:var(--radius-md); text-align:center;">
                        <p style="color:var(--danger-600); font-weight:600; font-size:var(--text-sm); margin-bottom:8px;">Terdeteksi Anomali Jantung!</p>
                        <a href="{{ url('pengaturan/kontak-darurat') }}" style="color:var(--danger-600); text-decoration:underline; font-size:var(--text-xs);">Atur Kontak Darurat Sekarang</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Smartwatch Data Table -->
<div class="chart-card animate-fade-in-up delay-400" style="margin-top:var(--space-6)">
    <div class="chart-header">
        <h5 class="chart-title">
            <i data-lucide="activity" style="width:20px;height:20px;color:var(--accent-500);vertical-align:middle"></i>
            Data Biometrik Terakhir
        </h5>
        <a href="{{ route('biometric') }}" class="btn btn-sm btn-secondary">Lihat Selengkapnya</a>
    </div>

    @if($recentBio->count())
    <div style="overflow-x:auto">
        <table class="data-table" id="bio-table" style="width:100%;text-align:left;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid var(--border-color);">
                    <th style="padding:var(--space-3);color:var(--text-muted);font-weight:600;font-size:var(--text-sm)">Waktu</th>
                    <th style="padding:var(--space-3);color:var(--text-muted);font-weight:600;font-size:var(--text-sm)">BPM</th>
                    <th style="padding:var(--space-3);color:var(--text-muted);font-weight:600;font-size:var(--text-sm)">HRV (ms)</th>
                    <th style="padding:var(--space-3);color:var(--text-muted);font-weight:600;font-size:var(--text-sm)">SpO2 (%)</th>
                    <th style="padding:var(--space-3);color:var(--text-muted);font-weight:600;font-size:var(--text-sm)">Kondisi</th>
                </tr>
            </thead>
            <tbody id="bio-tbody">
                @foreach($recentBio as $bio)
                <tr style="border-bottom:1px solid var(--border-color);">
                    <td style="padding:var(--space-3);font-size:var(--text-sm);">{{ $bio->recorded_at->format('d M Y, H:i:s') }}</td>
                    <td style="padding:var(--space-3);font-weight:600;color:{{ $bio->heart_rate > 100 ? 'var(--danger-600)' : 'var(--text-primary)' }}">{{ $bio->heart_rate }}</td>
                    <td style="padding:var(--space-3);font-weight:600;color:{{ $bio->hrv < 30 ? 'var(--danger-600)' : 'var(--text-primary)' }}">{{ $bio->hrv }}</td>
                    <td style="padding:var(--space-3);font-weight:600;color:{{ $bio->spo2 < 95 ? 'var(--danger-600)' : 'var(--text-primary)' }}">{{ $bio->spo2 }}</td>
                    <td style="padding:var(--space-3);">
                        @if($bio->heart_rate > 100 || $bio->hrv < 30)
                            <span style="background:rgba(239, 68, 68, 0.1);color:var(--danger-600);padding:2px 8px;border-radius:12px;font-size:12px;font-weight:700;">Stres/Anomali</span>
                        @else
                            <span style="background:rgba(34, 197, 94, 0.1);color:var(--success-600);padding:2px 8px;border-radius:12px;font-size:12px;font-weight:700;">Normal</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:var(--space-6);text-align:center;color:var(--text-muted)">
        Belum ada data biometrik yang terekam.
    </div>
    @endif
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
        gradient.addColorStop(0, 'rgba(155, 142, 196, 0.15)');
        gradient.addColorStop(1, 'rgba(155, 142, 196, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Mood Score',
                    data: [3, 4, 3, 5, 4, 4, 5],
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#9b8ec4',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#9b8ec4',
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
                        backgroundColor: '#302460',
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
});
</script>

</script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: '{{ env('REVERB_APP_KEY') }}',
        wsHost: '{{ env('REVERB_HOST', 'localhost') }}',
        wsPort: {{ env('REVERB_PORT', 8080) }},
        wssPort: {{ env('REVERB_PORT', 8080) }},
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
    });

    // Listen to WebSocket Events for real-time dashboard updates
    window.Echo.channel('biometric.{{ auth()->id() }}')
            .listen('BiometricUpdated', (e) => {
                const data = e.biometricData;
                
                // Update Metrics
                document.getElementById('val-bpm').textContent = data.heart_rate;
                document.getElementById('val-hrv').textContent = data.hrv;
                document.getElementById('val-spo2').textContent = data.spo2;
                
                const isAnomaly = data.heart_rate > 100 || data.hrv < 30;
                
                const statusEl = document.getElementById('val-status');
                statusEl.textContent = isAnomaly ? 'Bahaya' : 'Normal';
                statusEl.style.color = isAnomaly ? 'var(--danger-500)' : 'var(--success-500)';
                
                // Toggle WA Button
                document.getElementById('wa-button-container').style.display = isAnomaly ? 'block' : 'none';
                
                // Update Table
                const tbody = document.getElementById('bio-tbody');
                if (tbody) {
                    const time = new Date(data.recorded_at).toLocaleString('id-ID', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit'});
                    
                    const row = document.createElement('tr');
                    row.style.borderBottom = '1px solid var(--border-color)';
                    row.style.animation = 'fadeIn 0.5s ease-out';
                    
                    const bpmColor = data.heart_rate > 100 ? 'var(--danger-600)' : 'var(--text-primary)';
                    const hrvColor = data.hrv < 30 ? 'var(--danger-600)' : 'var(--text-primary)';
                    const spo2Color = data.spo2 < 95 ? 'var(--danger-600)' : 'var(--text-primary)';
                    
                    const kondisiHtml = isAnomaly 
                        ? '<span style="background:rgba(239, 68, 68, 0.1);color:var(--danger-600);padding:2px 8px;border-radius:12px;font-size:12px;font-weight:700;">Stres/Anomali</span>'
                        : '<span style="background:rgba(34, 197, 94, 0.1);color:var(--success-600);padding:2px 8px;border-radius:12px;font-size:12px;font-weight:700;">Normal</span>';
                    
                    row.innerHTML = `
                        <td style="padding:var(--space-3);font-size:var(--text-sm);">${time}</td>
                        <td style="padding:var(--space-3);font-weight:600;color:${bpmColor}">${data.heart_rate}</td>
                        <td style="padding:var(--space-3);font-weight:600;color:${hrvColor}">${data.hrv}</td>
                        <td style="padding:var(--space-3);font-weight:600;color:${spo2Color}">${data.spo2}</td>
                        <td style="padding:var(--space-3);">${kondisiHtml}</td>
                    `;
                    
                    tbody.insertBefore(row, tbody.firstChild);
                    
                    // Keep only 5 rows
                    if (tbody.children.length > 5) {
                        tbody.removeChild(tbody.lastChild);
                    }
                }
            });
</script>
@endpush
