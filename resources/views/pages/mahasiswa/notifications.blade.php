@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page_title', 'Notifikasi')
@section('page_subtitle', 'Peringatan dari sensor IoT smartwatch')

@push('styles')
<style>
    .alert-list { display:flex; flex-direction:column; gap:var(--space-3); }
    .alert-item { display:flex; align-items:flex-start; gap:var(--space-4); padding:var(--space-4); border-radius:var(--radius-xl); border:1px solid var(--border-color); background:var(--bg-card); transition:all 0.2s; }
    .alert-item:hover { border-color:var(--primary-200); }
    .alert-item.unread { border-left:3px solid var(--primary-500); background:var(--primary-50); }
    .alert-icon { width:40px; height:40px; border-radius:var(--radius-lg); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .alert-icon.warning { background:rgba(245,158,11,0.1); color:#b45309; }
    .alert-icon.danger { background:rgba(239,68,68,0.1); color:var(--danger-600); }
    .alert-icon.info { background:rgba(59,130,246,0.1); color:var(--primary-600); }
    .alert-body { flex:1; }
    .alert-title { font-weight:700; font-size:var(--text-sm); color:var(--text-primary); }
    .alert-message { font-size:var(--text-sm); color:var(--text-secondary); margin-top:2px; }
    .alert-time { font-size:var(--text-xs); color:var(--text-muted); margin-top:var(--space-1); }
</style>
@endpush

@section('content')
@if($alerts->count())
<div class="chart-card">
    <div class="chart-header"><h5 class="chart-title"><i data-lucide="bell" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i> Peringatan IoT</h5></div>
    <div class="alert-list">
        @foreach($alerts as $alert)
        <div class="alert-item {{ !$alert->is_read ? 'unread' : '' }}">
            <div class="alert-icon {{ $alert->severity }}">
                <i data-lucide="{{ $alert->severity === 'danger' ? 'alert-triangle' : ($alert->severity === 'warning' ? 'alert-circle' : 'info') }}" style="width:20px;height:20px"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">{{ $alert->alert_type }}</div>
                <div class="alert-message">{{ $alert->message }}</div>
                <div class="alert-time"><i data-lucide="clock" style="width:12px;height:12px;vertical-align:middle"></i> {{ $alert->alerted_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="margin-top:var(--space-4)">{{ $alerts->links() }}</div>
</div>
@else
<div class="chart-card" style="text-align:center;padding:var(--space-16)">
    <i data-lucide="bell-off" style="width:48px;height:48px;color:var(--gray-300);margin-bottom:var(--space-4)"></i>
    <h4 style="color:var(--text-muted)">Tidak Ada Notifikasi</h4>
    <p style="color:var(--text-muted)">Semua normal! Tidak ada peringatan dari sensor IoT.</p>
</div>
@endif
@endsection
