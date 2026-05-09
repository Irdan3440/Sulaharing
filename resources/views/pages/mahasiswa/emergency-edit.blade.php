@extends('layouts.app')

@section('title', 'Pengaturan Kontak Darurat')
@section('page_title', 'Kontak Darurat')
@section('page_subtitle', 'Masukkan nomor darurat Anda')

@push('styles')
<style>
    .emergency-container { max-width: 600px; margin: 0 auto; }
    .form-group { margin-bottom: var(--space-5); }
    .form-label { display: block; font-size: var(--text-sm); font-weight: 600; color: var(--text-primary); margin-bottom: var(--space-2); }
    .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-lg); font-size: var(--text-base); color: var(--text-primary); background: var(--bg-body); transition: all 0.2s; }
    .form-control:focus { border-color: var(--primary-500); outline: none; box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1); }
    .btn-submit { width: 100%; padding: 0.875rem; background: var(--gradient-primary); color: white; border: none; border-radius: var(--radius-lg); font-size: var(--text-base); font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
    .btn-submit:hover { opacity: 0.9; }
</style>
@endpush

@section('content')
<div class="emergency-container">

    @if (session('success'))
        <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.1); color: #15803d; padding: 1rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; display: flex; gap: 0.5rem; align-items: center;">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="chart-card">
        <div class="chart-header">
            <h5 class="chart-title">
                <i data-lucide="shield-alert" style="width:20px;height:20px;color:var(--primary-500);vertical-align:middle"></i>
                Data Kontak Darurat
            </h5>
        </div>
        
        <form method="POST" action="{{ route('emergency.update') }}" style="padding-top: 1rem;">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kontak Darurat</label>
                <input type="text" name="name" value="{{ old('name', $contact->name) }}" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Hubungan (misal: Orang Tua, Sahabat)</label>
                <input type="text" name="relationship" value="{{ old('relationship', $contact->relationship) }}" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}" required placeholder="+628xxxxxx" class="form-control">
            </div>

            <button type="submit" class="btn-submit">
                Simpan Kontak Darurat
            </button>
        </form>
    </div>
</div>
@endsection
