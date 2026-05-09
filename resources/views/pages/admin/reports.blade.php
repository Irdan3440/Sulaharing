@extends('layouts.app')
@section('title', 'Laporan')
@section('page_title', 'Laporan Konsultasi')
@section('page_subtitle', 'Filter dan analisis data konsultasi per fakultas')

@section('sidebar')
<div class="sidebar-section"><div class="sidebar-section-title">Administrasi</div><ul>
    <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link"><i data-lucide="users"></i> Pengguna</a></li>
    <li><a href="{{ route('admin.reports') }}" class="sidebar-link active"><i data-lucide="bar-chart-3"></i> Laporan</a></li>
</ul></div>
@endsection

@push('styles')
<style>
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th, .data-table td { padding:0.75rem 1rem; text-align:left; border-bottom:1px solid var(--border-color); font-size:var(--text-sm); }
    .data-table th { font-weight:700; color:var(--text-secondary); font-size:var(--text-xs); text-transform:uppercase; background:var(--gray-50); }
    .data-table tbody tr:hover { background:var(--primary-50); }
    .filter-bar { display:flex; gap:var(--space-3); margin-bottom:var(--space-5); flex-wrap:wrap; }
    .badge-class { padding:3px 10px; border-radius:var(--radius-full); font-weight:700; font-size:10px; }
    .badge-class.minimal { background:rgba(34,197,94,0.1); color:var(--success-600); }
    .badge-class.ringan { background:rgba(245,158,11,0.1); color:#b45309; }
    .badge-class.sedang { background:rgba(249,115,22,0.1); color:#ea580c; }
    .badge-class.berat { background:rgba(239,68,68,0.1); color:var(--danger-600); }
</style>
@endpush

@section('content')
<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">Laporan Konsultasi ({{ $consultations->total() }})</h5>
    </div>

    <form method="GET" class="filter-bar">
        <select name="fakultas" class="form-input" style="max-width:160px">
            <option value="">Semua Fakultas</option>
            @foreach($fakultasList as $f) <option value="{{ $f }}" {{ request('fakultas')==$f?'selected':'' }}>{{ $f }}</option> @endforeach
        </select>
        <select name="classification" class="form-input" style="max-width:160px">
            <option value="">Semua Klasifikasi</option>
            <option value="Minimal" {{ request('classification')=='Minimal'?'selected':'' }}>Minimal</option>
            <option value="Ringan" {{ request('classification')=='Ringan'?'selected':'' }}>Ringan</option>
            <option value="Sedang" {{ request('classification')=='Sedang'?'selected':'' }}>Sedang</option>
            <option value="Berat" {{ request('classification')=='Berat'?'selected':'' }}>Berat</option>
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="form-input" style="max-width:160px" placeholder="Dari">
        <input type="date" name="to" value="{{ request('to') }}" class="form-input" style="max-width:160px" placeholder="Sampai">
        <button class="btn btn-sm btn-primary" type="submit"><i data-lucide="filter" style="width:14px;height:14px"></i> Filter</button>

    </form>

    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr><th>Tanggal</th><th>Nama</th><th>Fakultas</th><th>Skor BDI</th><th>CF%</th><th>Klasifikasi</th></tr></thead>
        <tbody>
        @foreach($consultations as $c)
            @php $slug = strtolower($c->classification); @endphp
            <tr>
                <td>{{ $c->consulted_at->format('d M Y H:i') }}</td>
                <td><strong>{{ $c->user->name ?? $c->guest_name ?? 'Tamu' }}</strong></td>
                <td>{{ $c->user->fakultas ?? $c->guest_institusi ?? '-' }}</td>
                <td>{{ $c->total_score_bdi }}</td>
                <td style="font-weight:700">{{ $c->cf_percentage }}%</td>
                <td><span class="badge-class {{ $slug }}">{{ $c->classification }}</span></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div style="margin-top:var(--space-4)">{{ $consultations->withQueryString()->links() }}</div>
</div>
@endsection
