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
                <svg width="36" height="36" viewBox="0 0 48 48" fill="none">
                    <defs><linearGradient id="qGrad" x1="0" y1="0" x2="48" y2="48"><stop offset="0%" stop-color="#8b5cf6"/><stop offset="100%" stop-color="#3b82f6"/></linearGradient></defs>
                    <path d="M24 4C18.5 4 14 8.5 14 14c0 3.5 1.8 6.6 4.5 8.4L12 35c-1 2 .5 4 2.5 4h19c2 0 3.5-2 2.5-4l-6.5-12.6C32.2 20.6 34 17.5 34 14c0-5.5-4.5-10-10-10z" fill="url(#qGrad)"/>
                    <path d="M20 16c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <circle cx="24" cy="20" r="2" fill="white"/>
                </svg>
            </a>
            <h2>Kuesioner Depresi</h2>
            <p>Jawab setiap pertanyaan sesuai dengan perasaan Anda dalam <strong>2 minggu terakhir</strong></p>
        </div>

        @php
            $symptomList = isset($symptoms) ? $symptoms : collect();
            $totalQ = $symptomList->count() ?: 21;
        @endphp

        <!-- Progress Bar -->
        <div class="progress-wrapper" style="margin-bottom:var(--space-8);">
            <div class="questionnaire-progress" style="flex:1">
                <div class="questionnaire-progress-fill" id="progressFill" style="width: 0%"></div>
            </div>
            <span class="progress-step-counter" id="stepCounter">0/{{ $totalQ }}</span>
        </div>

        <!-- Questions Form -->
        <form method="POST" action="{{ auth()->check() ? url('/konsultasi/result') : url('/diagnosa/result') }}" id="questionnaire-form">
            @csrf
            <input type="hidden" name="guest_name" value="{{ $guest_name ?? '' }}">
            <input type="hidden" name="guest_institusi" value="{{ $guest_institusi ?? '' }}">
            <input type="hidden" name="guest_usia" value="{{ $guest_usia ?? '' }}">

            @foreach($symptomList as $idx => $symptom)
            @php $num = $idx + 1; @endphp
            <div class="question-card question-slide" id="question-{{ $num }}" style="{{ $num > 1 ? 'display:none;' : '' }}">
                <span class="question-number">
                    Pertanyaan {{ $num }}
                    <span class="question-aspect">{{ ucfirst($symptom->aspek) }}</span>
                </span>
                <p class="question-text">{{ $num }}. {{ $symptom->deskripsi }}</p>

                <div class="answer-options">
                    @foreach($symptom->pilihan_jawaban as $i => $option)
                    <div class="answer-option">
                        <input type="radio" name="answer_{{ $symptom->id }}" id="q{{ $symptom->id }}_a{{ $i }}" value="{{ $i }}" required>
                        <label for="q{{ $symptom->id }}_a{{ $i }}">
                            <span class="answer-score">{{ $i }}</span>
                            <span class="answer-text">{{ $option }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Navigation -->
            <div class="questionnaire-nav">
                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="navigateQuestion(-1)" style="visibility:hidden">
                    <i data-lucide="arrow-left" style="width:18px;height:18px"></i>
                    Sebelumnya
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="navigateQuestion(1)">
                    Selanjutnya
                    <i data-lucide="arrow-right" style="width:18px;height:18px"></i>
                </button>
                <button type="submit" class="btn btn-success btn-lg" id="submitBtn" style="display:none">
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
    let currentQuestion = 1;
    const totalQuestions = {{ $totalQ }};

    function updateProgress() {
        const answered = document.querySelectorAll('.answer-option input:checked').length;
        const pct = (answered / totalQuestions) * 100;
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('stepCounter').textContent = answered + '/' + totalQuestions;
    }

    function navigateQuestion(direction) {
        const current = document.getElementById('question-' + currentQuestion);
        const next = currentQuestion + direction;

        if (direction === 1) {
            const selectedAnswer = current.querySelector('input[type="radio"]:checked');
            if (!selectedAnswer) {
                current.style.animation = 'none';
                current.offsetHeight;
                current.style.animation = 'scaleIn 0.3s ease-out';
                current.style.borderColor = 'var(--danger-400)';
                setTimeout(() => { current.style.borderColor = ''; }, 1500);
                return;
            }
        }

        if (next < 1 || next > totalQuestions) return;

        current.style.display = 'none';
        currentQuestion = next;
        const nextCard = document.getElementById('question-' + currentQuestion);
        nextCard.style.display = 'block';
        nextCard.style.animation = direction > 0 ? 'slideInRight 0.4s ease-out' : 'slideInLeft 0.4s ease-out';

        document.getElementById('prevBtn').style.visibility = currentQuestion === 1 ? 'hidden' : 'visible';

        if (currentQuestion === totalQuestions) {
            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('submitBtn').style.display = 'inline-flex';
        } else {
            document.getElementById('nextBtn').style.display = 'inline-flex';
            document.getElementById('submitBtn').style.display = 'none';
        }

        updateProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.answer-option input').forEach(input => {
        input.addEventListener('change', updateProgress);
    });

    updateProgress();
</script>
@endpush
