@extends('layouts.app')

@section('title', 'Tele-Counseling')
@section('page_title', 'Tele-Counseling')
@section('page_subtitle', 'Hubungi psikolog kampus secara langsung')

@push('styles')
<style>
    .tc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: var(--space-5); }
    .tc-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-2xl); padding: var(--space-5); display: flex; align-items: center; gap: var(--space-5); transition: box-shadow 0.3s, transform 0.3s; }
    .tc-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .tc-photo { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; background: var(--bg-body); padding: 4px; border: 2px solid var(--primary-100); }
    .tc-info h3 { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; }
    .tc-info p { font-size: var(--text-sm); color: var(--text-muted); margin-bottom: 1rem; }
    .tc-btn { display: inline-flex; items-center; gap: 0.5rem; background: var(--gradient-teal); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-lg); font-size: var(--text-sm); font-weight: 600; text-decoration: none; transition: opacity 0.2s; }
    .tc-btn:hover { opacity: 0.9; }
</style>
@endpush

@section('content')
<div class="tc-grid">
    @foreach ($psychologists as $doc)
        <div class="tc-card">
            <img src="{{ asset($doc['photo']) }}" alt="{{ $doc['name'] }}" class="tc-photo">
            <div class="tc-info">
                <h3>{{ $doc['name'] }}</h3>
                <p>WhatsApp: {{ $doc['phone'] }}</p>
                
                @php
                    $msg = urlencode("Halo {$doc['name']}, saya mahasiswa ".auth()->user()->name." butuh bantuan psikologis. Bisa konsultasi via chat?");
                    $waLink = "https://wa.me/" . preg_replace('/\D/', '', $doc['phone']) . "?text={$msg}";
                @endphp

                <a href="{{ $waLink }}" target="_blank" class="tc-btn">
                    <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                    Konsultasi Sekarang
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection
