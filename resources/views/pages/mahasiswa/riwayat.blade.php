@extends('layouts.app')
@section('title', 'Riwayat Konsultasi')
@section('page_title', 'Riwayat')
@section('page_subtitle', 'Histori konsultasi kesehatan mental Anda')

@section('content')
@if($consultations->count())
<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title"><i data-lucide="history" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i> Riwayat Konsultasi</h5>
    </div>
    <div class="recent-list">
        @foreach($consultations as $c)
        @php
            $slug = strtolower($c->classification);
            $colors = ['minimal'=>'#22c55e','ringan'=>'#f59e0b','sedang'=>'#f97316','berat'=>'#ef4444'];
        @endphp
        <a href="{{ route('riwayat.detail', $c->id) }}" style="text-decoration:none;color:inherit">
            <div class="recent-item" style="cursor:pointer">
                <div class="recent-item-date">
                    <span class="recent-item-day">{{ $c->consulted_at->format('d') }}</span>
                    <span class="recent-item-month">{{ $c->consulted_at->format('M Y') }}</span>
                </div>
                <div class="recent-item-info">
                    <div class="recent-item-title">{{ $c->disease->nama ?? $c->classification }}</div>
                    <div class="recent-item-subtitle">Skor BDI: {{ $c->total_score_bdi }} — CF: {{ $c->cf_percentage }}%</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px">
                    <span style="padding:3px 10px;border-radius:var(--radius-full);font-weight:700;font-size:10px;background:{{ $colors[$slug] ?? '#6b7280' }}15;color:{{ $colors[$slug] ?? '#6b7280' }}">{{ $c->classification }}</span>
                    <i data-lucide="chevron-right" style="width:16px;height:16px;color:var(--gray-400)"></i>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div style="margin-top:var(--space-4)">{{ $consultations->links() }}</div>
</div>
@else
<div class="chart-card" style="text-align:center;padding:var(--space-16)">
    <i data-lucide="clipboard-x" style="width:48px;height:48px;color:var(--gray-300);margin-bottom:var(--space-4)"></i>
    <h4 style="color:var(--text-muted)">Belum Ada Riwayat</h4>
    <p style="color:var(--text-muted);margin-bottom:var(--space-6)">Anda belum pernah melakukan konsultasi. Mulai sekarang!</p>
    <a href="{{ route('konsultasi') }}" class="btn btn-primary"><i data-lucide="stethoscope" style="width:18px;height:18px"></i> Mulai Konsultasi</a>
</div>
@endif
@endsection
