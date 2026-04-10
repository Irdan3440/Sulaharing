@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Ringkasan sistem SulaHaring')

@section('sidebar')
<div class="sidebar-section"><div class="sidebar-section-title">Administrasi</div><ul>
    <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link"><i data-lucide="users"></i> Pengguna</a></li>
    <li><a href="{{ route('admin.reports') }}" class="sidebar-link"><i data-lucide="bar-chart-3"></i> Laporan</a></li>
</ul></div>
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon blue"><i data-lucide="users"></i></div>
        <div class="stat-info"><div class="stat-label">Total Pengguna</div><div class="stat-value">{{ $totalUsers }}</div></div></div>
    <div class="stat-card"><div class="stat-icon green"><i data-lucide="graduation-cap"></i></div>
        <div class="stat-info"><div class="stat-label">Mahasiswa</div><div class="stat-value">{{ $totalMahasiswa }}</div></div></div>
    <div class="stat-card"><div class="stat-icon violet"><i data-lucide="shield"></i></div>
        <div class="stat-info"><div class="stat-label">Pakar</div><div class="stat-value">{{ $totalPakar }}</div></div></div>
    <div class="stat-card"><div class="stat-icon cyan"><i data-lucide="clipboard-check"></i></div>
        <div class="stat-info"><div class="stat-label">Konsultasi</div><div class="stat-value">{{ $totalConsultations }}</div></div></div>
</div>

<div class="dashboard-grid dashboard-grid-equal" style="margin-top:var(--space-6)">
    <!-- Faculty Distribution -->
    <div class="chart-card">
        <div class="chart-header"><h5 class="chart-title"><i data-lucide="building-2" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i> Distribusi Fakultas</h5></div>
        <div class="chart-container"><canvas id="fakultasChart"></canvas></div>
    </div>
    <!-- Classification Distribution -->
    <div class="chart-card">
        <div class="chart-header"><h5 class="chart-title"><i data-lucide="pie-chart" style="width:20px;height:20px;color:var(--violet-500);vertical-align:middle"></i> Klasifikasi Depresi</h5></div>
        <div class="chart-container"><canvas id="classChart"></canvas></div>
    </div>
</div>

<!-- Recent Users -->
<div class="chart-card" style="margin-top:var(--space-6)">
    <div class="chart-header">
        <h5 class="chart-title"><i data-lucide="user-plus" style="width:20px;height:20px;color:var(--success-600);vertical-align:middle"></i> Pengguna Terbaru</h5>
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="recent-list">
        @foreach($recentUsers as $u)
        <div class="recent-item">
            <div class="recent-item-date" style="background:var(--gradient-primary);">
                <span class="recent-item-day" style="color:white">{{ strtoupper(substr($u->name,0,1)) }}</span>
            </div>
            <div class="recent-item-info">
                <div class="recent-item-title">{{ $u->name }}</div>
                <div class="recent-item-subtitle">{{ $u->email }} — {{ ucfirst($u->role) }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
new Chart(document.getElementById('fakultasChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($fakultasStats->toArray())) !!},
        datasets: [{ data: {!! json_encode(array_values($fakultasStats->toArray())) !!}, backgroundColor: '#3b82f6', borderRadius: 8, barThickness: 28 }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,grid:{color:'rgba(0,0,0,0.04)'}},x:{grid:{display:false}}} }
});
new Chart(document.getElementById('classChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($classStats->toArray())) !!},
        datasets: [{ data: {!! json_encode(array_values($classStats->toArray())) !!}, backgroundColor:['#22c55e','#f59e0b','#f97316','#ef4444'], borderWidth:0 }]
    },
    options: { responsive:true, maintainAspectRatio:false, cutout:'65%', plugins:{legend:{position:'bottom',labels:{font:{family:'Inter',size:12},padding:16}}} }
});
</script>
@endpush
