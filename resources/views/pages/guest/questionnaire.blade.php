@extends('layouts.guest')

@section('title', 'Kuesioner Depresi — SulaHaring')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/questionnaire.css') }}">
@endpush

@section('content')
<div class="questionnaire-page">
    <div class="questionnaire-container">

        <!-- Logo Header -->
        <div class="questionnaire-header">
            <a href="{{ url('/') }}" style="display:inline-flex; text-decoration:none; margin-bottom:var(--space-4);">
                <img src="{{ asset('images/logo-sulaharing.png') }}" alt="SulaHaring" style="width:48px;height:48px;object-fit:contain">
            </a>
            <span class="questionnaire-brand">SulaHaring</span>
            <h2>Konsultasi Kesehatan Mental</h2>
            <p>Evaluasi kesehatan mental anda dengan sistem diagnosa berbasis kecerdasan buatan</p>
        </div>

        @php
            $symptomList = isset($symptoms) ? $symptoms : collect();
            $totalQ = $symptomList->count() ?: 21;
        @endphp

        <!-- Section Title -->
        <div class="section-divider">
            <h3>Kuesioner Depresi</h3>
        </div>

        <!-- All Questions Form -->
        <form method="POST" action="{{ auth()->check() ? url('/konsultasi/result') : url('/diagnosa/result') }}" id="questionnaire-form">
            @csrf
            <input type="hidden" name="guest_name" value="{{ $guest_name ?? '' }}">
            <input type="hidden" name="guest_institusi" value="{{ $guest_institusi ?? '' }}">
            <input type="hidden" name="guest_usia" value="{{ $guest_usia ?? '' }}">

            @foreach($symptomList as $idx => $symptom)
            @php $num = $idx + 1; @endphp
            <div class="question-card-full">
                <p class="question-text-full">
                    <strong>{{ $num }}.</strong> {{ $symptom->deskripsi }}
                </p>

                <div class="answer-grid">
                    @foreach($symptom->pilihan_jawaban as $i => $option)
                    <label class="answer-chip" for="q{{ $symptom->id }}_a{{ $i }}">
                        <input type="radio" name="answer_{{ $symptom->id }}" id="q{{ $symptom->id }}_a{{ $i }}" value="{{ $i }}" required>
                        <span class="chip-dot">{{ $i }}</span>
                        <span class="chip-text">{{ $option }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Progress Info -->
            <div class="questionnaire-progress-info">
                <span id="answeredCount">0</span> / {{ $totalQ }} pertanyaan terjawab
            </div>

            <!-- Submit -->
            <div class="questionnaire-submit">
                <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitBtn">
                    <i data-lucide="check-circle" style="width:20px;height:20px"></i>
                    Lihat Hasil Diagnosa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Track answered questions
    function updateAnsweredCount() {
        const answered = document.querySelectorAll('.answer-chip input:checked').length;
        document.getElementById('answeredCount').textContent = answered;
    }

    // Highlight selected answer
    document.querySelectorAll('.answer-chip input').forEach(input => {
        input.addEventListener('change', function() {
            // Remove active from siblings
            const parent = this.closest('.answer-grid');
            parent.querySelectorAll('.answer-chip').forEach(chip => chip.classList.remove('selected'));
            // Add active to selected
            this.closest('.answer-chip').classList.add('selected');
            updateAnsweredCount();
        });
    });

    updateAnsweredCount();
</script>
@endpush
