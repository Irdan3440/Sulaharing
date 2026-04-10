@extends('layouts.app')
@section('title', 'Detail Konsultasi')
@section('page_title', 'Detail Konsultasi')
@section('page_subtitle', 'Analisis {{ $consultation->consulted_at->format("d M Y") }}')

@push('styles')
<style>
    .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:var(--space-6); }
    .detail-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-2xl); padding:var(--space-6); }
    .score-circle { width:120px; height:120px; border-radius:50%; display:flex; flex-direction:column; align-items:center; justify-content:center; margin:0 auto var(--space-4); }
    .score-circle .value { font-size:2rem; font-weight:800; font-family:var(--font-heading); }
    .score-circle .label { font-size:var(--text-xs); opacity:0.8; }
    .symptom-row { display:flex; align-items:center; padding:0.5rem 0; border-bottom:1px solid var(--border-color); gap:var(--space-3); font-size:var(--text-sm); }
    .symptom-score { width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:11px; flex-shrink:0; }
    .symptom-score.s0 { background:rgba(34,197,94,0.1); color:var(--success-600); }
    .symptom-score.s1 { background:rgba(245,158,11,0.1); color:#b45309; }
    .symptom-score.s2 { background:rgba(249,115,22,0.1); color:#ea580c; }
    .symptom-score.s3 { background:rgba(239,68,68,0.1); color:var(--danger-600); }
    @media(max-width:768px) { .detail-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div style="margin-bottom:var(--space-4); display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('riwayat') }}" class="btn btn-sm btn-secondary"><i data-lucide="arrow-left" style="width:16px;height:16px"></i> Kembali</a>
    <a href="{{ route('riwayat.pdf', $consultation->id) }}" class="btn btn-sm btn-primary"><i data-lucide="download" style="width:16px;height:16px"></i> Unduh Laporan PDF</a>
</div>

<div class="detail-grid">
    <!-- Result Card -->
    <div class="detail-card" style="text-align:center">
        @php
            $slug = strtolower($consultation->classification);
            $colors = ['minimal'=>'#22c55e','ringan'=>'#f59e0b','sedang'=>'#f97316','berat'=>'#ef4444'];
            $color = $colors[$slug] ?? '#6b7280';
        @endphp
        <div class="score-circle" style="background:{{ $color }}15; color:{{ $color }}">
            <span class="value">{{ $consultation->cf_percentage }}%</span>
            <span class="label">CF Score</span>
        </div>
        <h3>{{ $consultation->disease->nama ?? $consultation->classification }}</h3>
        <p style="color:var(--text-muted);margin-top:var(--space-2)">Skor BDI-II: {{ $consultation->total_score_bdi }} / 63</p>
        <p style="margin-top:var(--space-4);font-size:var(--text-sm);color:var(--text-secondary)">{{ $consultation->disease->deskripsi ?? '' }}</p>
    </div>

    <!-- Saran Card -->
    <div class="detail-card">
        <h5 style="margin-bottom:var(--space-4)"><i data-lucide="heart-handshake" style="width:20px;height:20px;color:{{ $color }};vertical-align:middle"></i> Saran Penanganan</h5>
        <p style="color:var(--text-secondary);line-height:1.7">{{ $consultation->saran ?: 'Tidak ada saran khusus.' }}</p>
        @if($consultation->disease && $consultation->disease->rujukan_helpdesk)
        <div style="margin-top:var(--space-4);padding:var(--space-4);background:var(--primary-50);border-radius:var(--radius-xl)">
            <p style="font-weight:700;font-size:var(--text-sm);margin-bottom:var(--space-1)"><i data-lucide="phone" style="width:14px;height:14px;vertical-align:middle"></i> Rujukan</p>
            <p style="font-size:var(--text-sm);color:var(--primary-700)">{{ $consultation->disease->rujukan_helpdesk }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Detail Per Symptom -->
<div class="detail-card" style="margin-top:var(--space-6)">
    <h5 style="margin-bottom:var(--space-4)"><i data-lucide="list-checks" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i> Detail Per Gejala</h5>
    @foreach($consultation->details as $detail)
    <div class="symptom-row">
        <span class="symptom-score s{{ $detail->answer_score }}">{{ $detail->answer_score }}</span>
        <span style="flex:1;font-weight:600">{{ $detail->symptom->kode ?? '' }} — {{ $detail->symptom->nama ?? '' }}</span>
        <span style="font-size:var(--text-xs);color:var(--text-muted)">CF: {{ number_format($detail->cf_user, 2) }}</span>
    </div>
    @endforeach
</div>
@endsection
