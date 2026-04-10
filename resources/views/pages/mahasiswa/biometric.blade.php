@extends('layouts.app')
@section('title', 'Data Biometrik')
@section('page_title', 'Biometrik')
@section('page_subtitle', 'Monitoring data dari IoT smartwatch')

@push('styles')
<style>
    .bio-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:var(--space-5); margin-bottom:var(--space-6); }
    .bio-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-2xl); padding:var(--space-6); text-align:center; }
    .bio-card .bio-icon { width:48px; height:48px; border-radius:var(--radius-xl); display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-3); }
    .bio-card .bio-value { font-size:2rem; font-weight:800; font-family:var(--font-heading); color:var(--text-primary); }
    .bio-card .bio-label { font-size:var(--text-sm); color:var(--text-muted); }
    .bio-card .bio-status { font-size:var(--text-xs); font-weight:700; margin-top:var(--space-1); }
    @media(max-width:768px) { .bio-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<!-- Latest Values -->
<div class="bio-grid">
    <div class="bio-card">
        <div class="bio-icon" style="background:rgba(239,68,68,0.1)"><i data-lucide="heart-pulse" style="width:24px;height:24px;color:var(--danger-500)"></i></div>
        <div class="bio-value">{{ $latest->heart_rate ?? '--' }}</div>
        <div class="bio-label">BPM (Heart Rate)</div>
        <div class="bio-status" style="color:{{ ($latest && $latest->heart_rate > 100) ? 'var(--danger-600)' : 'var(--success-600)' }}">
            {{ $latest ? ($latest->heart_rate > 100 ? '⚠ Tinggi' : ($latest->heart_rate < 50 ? '⚠ Rendah' : '✓ Normal')) : 'N/A' }}
        </div>
    </div>
    <div class="bio-card">
        <div class="bio-icon" style="background:rgba(139,92,246,0.1)"><i data-lucide="activity" style="width:24px;height:24px;color:var(--violet-500)"></i></div>
        <div class="bio-value">{{ $latest->hrv ?? '--' }}</div>
        <div class="bio-label">HRV (ms)</div>
        <div class="bio-status" style="color:var(--success-600)">{{ $latest ? '✓ Normal' : 'N/A' }}</div>
    </div>
    <div class="bio-card">
        <div class="bio-icon" style="background:rgba(6,182,212,0.1)"><i data-lucide="droplets" style="width:24px;height:24px;color:var(--accent-500)"></i></div>
        <div class="bio-value">{{ $latest->spo2 ?? '--' }}<span style="font-size:var(--text-base)">%</span></div>
        <div class="bio-label">SpO2</div>
        <div class="bio-status" style="color:{{ ($latest && $latest->spo2 && $latest->spo2 < 95) ? 'var(--danger-600)' : 'var(--success-600)' }}">
            {{ $latest && $latest->spo2 ? ($latest->spo2 < 95 ? '⚠ Rendah' : '✓ Normal') : 'N/A' }}
        </div>
    </div>
</div>

<!-- Charts -->
<div class="chart-card">
    <div class="chart-header"><h5 class="chart-title"><i data-lucide="trending-up" style="width:20px;height:20px;color:var(--danger-500);vertical-align:middle"></i> Grafik Heart Rate</h5></div>
    <div class="chart-container" style="height:300px"><canvas id="hrChart"></canvas></div>
</div>

@if($data->isEmpty())
<div class="chart-card" style="text-align:center;padding:var(--space-12);margin-top:var(--space-6)">
    <i data-lucide="watch" style="width:48px;height:48px;color:var(--gray-300);margin-bottom:var(--space-4)"></i>
    <h4 style="color:var(--text-muted)">Belum Ada Data Biometrik</h4>
    <p style="color:var(--text-muted)">Hubungkan smartwatch IoT Anda untuk mulai memantau.</p>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const bioData = @json($data);
if (bioData.length) {
    new Chart(document.getElementById('hrChart'), {
        type: 'line',
        data: {
            labels: bioData.map(d => new Date(d.recorded_at).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'})),
            datasets: [{
                label: 'BPM', data: bioData.map(d => d.heart_rate), borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.08)', fill: true, tension: 0.4, pointRadius: 2
            }]
        },
        options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:false,grid:{color:'rgba(0,0,0,0.04)'}},x:{grid:{display:false},ticks:{maxTicksLimit:10}}} }
    });
}
</script>
@endpush
