@extends('layouts.app')

@section('title', 'Dashboard Pakar')
@section('page_title', 'Dashboard Pakar')
@section('page_subtitle', 'Manajemen Basis Pengetahuan Sistem Pakar')

@section('sidebar')
<div class="sidebar-section">
    <div class="sidebar-section-title">Menu Utama</div>
    <ul>
        <li><a href="{{ route('pakar.dashboard') }}" class="sidebar-link active"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('pakar.diseases') }}" class="sidebar-link"><i data-lucide="heart-crack"></i> Penyakit</a></li>
        <li><a href="{{ route('pakar.symptoms') }}" class="sidebar-link"><i data-lucide="stethoscope"></i> Gejala</a></li>
        <li><a href="{{ route('pakar.rules') }}" class="sidebar-link"><i data-lucide="settings-2"></i> Aturan CF</a></li>
    </ul>
</div>
@endsection

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon violet"><i data-lucide="heart-crack"></i></div>
        <div class="stat-info">
            <div class="stat-label">Penyakit</div>
            <div class="stat-value">{{ $totalDiseases }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i data-lucide="list-checks"></i></div>
        <div class="stat-info">
            <div class="stat-label">Gejala</div>
            <div class="stat-value">{{ $totalSymptoms }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cyan"><i data-lucide="settings-2"></i></div>
        <div class="stat-info">
            <div class="stat-label">Aturan CF</div>
            <div class="stat-value">{{ $totalRules }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i data-lucide="clipboard-check"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Konsultasi</div>
            <div class="stat-value">{{ $totalConsultations }}</div>
        </div>
    </div>
</div>

<!-- Classification Distribution -->
<div class="dashboard-grid dashboard-grid-equal" style="margin-top:var(--space-6)">
    <div class="chart-card">
        <div class="chart-header">
            <h5 class="chart-title"><i data-lucide="pie-chart" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i> Distribusi Klasifikasi</h5>
        </div>
        <div class="chart-container"><canvas id="classChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="chart-header">
            <h5 class="chart-title"><i data-lucide="alert-triangle" style="width:20px;height:20px;color:#ea580c;vertical-align:middle"></i> Mahasiswa Risiko Tinggi</h5>
        </div>
        @if($highRiskStudents->count())
        <div class="recent-list">
            @foreach($highRiskStudents as $hr)
            <div class="recent-item">
                <div class="recent-item-date" style="background:linear-gradient(135deg,rgba(239,68,68,0.1),rgba(239,68,68,0.05));">
                    <span class="recent-item-day" style="color:var(--danger-600)">{{ round($hr->cf_percentage) }}</span>
                    <span class="recent-item-month">%</span>
                </div>
                <div class="recent-item-info">
                    <div class="recent-item-title">{{ $hr->user->name ?? 'Tamu' }}</div>
                    <div class="recent-item-subtitle">{{ $hr->classification }} — {{ $hr->consulted_at->format('d M Y') }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:var(--text-muted);padding:var(--space-8)">Tidak ada mahasiswa berisiko tinggi saat ini.</p>
        @endif
    </div>
</div>

<!-- Recent Consultations -->
<div class="chart-card" style="margin-top:var(--space-6)">
    <div class="chart-header">
        <h5 class="chart-title"><i data-lucide="clock" style="width:20px;height:20px;color:var(--violet-500);vertical-align:middle"></i> Konsultasi Terbaru</h5>
    </div>
    <div class="recent-list">
        @foreach($recentConsultations as $c)
        <div class="recent-item">
            <div class="recent-item-date">
                <span class="recent-item-day">{{ $c->consulted_at->format('d') }}</span>
                <span class="recent-item-month">{{ $c->consulted_at->format('M') }}</span>
            </div>
            <div class="recent-item-info">
                <div class="recent-item-title">{{ $c->user->name ?? $c->guest_name ?? 'Tamu' }}</div>
                <div class="recent-item-subtitle">{{ $c->disease->nama ?? $c->classification }} — Skor BDI: {{ $c->total_score_bdi }}</div>
            </div>
            <div class="recent-item-score {{ $c->class_slug }}">{{ $c->cf_percentage }}%</div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
new Chart(document.getElementById('classChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($classStats->toArray())) !!},
        datasets: [{
            data: {!! json_encode(array_values($classStats->toArray())) !!},
            backgroundColor: ['#22c55e', '#f59e0b', '#f97316', '#ef4444'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 12 }, padding: 16 } } },
        cutout: '65%',
    }
});
</script>
@endpush
