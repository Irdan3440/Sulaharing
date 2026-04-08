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

        <!-- Progress Bar -->
        <div class="progress-wrapper" style="margin-bottom:var(--space-8);">
            <div class="questionnaire-progress" style="flex:1">
                <div class="questionnaire-progress-fill" id="progressFill" style="width: 0%"></div>
            </div>
            <span class="progress-step-counter" id="stepCounter">0/21</span>
        </div>

        <!-- Questions Form -->
        <form method="POST" action="{{ url('/diagnosa/result') }}" id="questionnaire-form">
            @csrf
            <input type="hidden" name="guest_name" value="{{ $guest_name ?? '' }}">
            <input type="hidden" name="guest_institusi" value="{{ $guest_institusi ?? '' }}">
            <input type="hidden" name="guest_usia" value="{{ $guest_usia ?? '' }}">

            @php
            $questions = [
                ['num' => 1, 'aspect' => 'Afektif', 'text' => 'Mengukur tingkat kesedihan yang dialami mahasiswa terkait situasi akademik dan kehidupan kampus.', 'options' => [
                    'Saya tidak merasa sedih',
                    'Saya merasa sedih sekali',
                    'Saya sering merasa sedih, terutama terkait tugas atau kegiatan kuliah',
                    'Saya sangat sedih sekali hampir sepanjang waktu sehingga sulit merasa sulit fokus sekali'
                ]],
                ['num' => 2, 'aspect' => 'Kognitif', 'text' => 'Mengukir tingkat bersalah yang dialami mahasiswa terkait performa akademik dan tanggung jawab.', 'options' => [
                    'Saya jarang merasa bersalah atas hal-hal yang saya lakukan di kampus',
                    'Saya kadang merasa bersalah karena tidak maksimal belajar',
                    'Saya sering merasa bersalah kepada dosen, teman, atau keluarga',
                    'Saya selalu merasa bersalah atas kegagalan saya sebagai mahasiswa'
                ]],
                ['num' => 3, 'aspect' => 'Kognitif', 'text' => 'Mengukir adanya pikiran atau keinginan untuk bunuh diri yang mungkin muncul akibat tekanan akademik.', 'options' => [
                    'Saya tidak merasa sedih',
                    'Saya merasa sedih sekali',
                    'Saya sering merasa sedih, terutama',
                    'Saya sangat sedih sekali hampir'
                ]],
                ['num' => 4, 'aspect' => 'Afektif', 'text' => 'Mengukur seberapa sering mahasiswa menangis terkait tekanan emosional dari lingkungan akademik.', 'options' => [
                    'Saya tidak lebih sering menangis dari biasanya',
                    'Saya kadang menangis setelah mendapat hasil ujian buruk',
                    'Saya sering menangis karena tekanan kuliah',
                    'Saya selalu ingin menangis tapi kadang tidak bisa'
                ]],
                ['num' => 5, 'aspect' => 'Kognitif', 'text' => 'Mengukur tingkat pesimisme mahasiswa terhadap masa depan akademik dan karier.', 'options' => [
                    'Saya tidak merasa pesimis tentang masa depan',
                    'Saya kadang merasa ragu dengan prospek karier',
                    'Saya merasa tidak yakin akan berhasil setelah lulus',
                    'Saya merasa masa depan saya suram dan tidak ada harapan'
                ]],
                ['num' => 6, 'aspect' => 'Kognitif', 'text' => 'Mengukur perasaan gagal yang dialami mahasiswa dalam konteks akademik.', 'options' => [
                    'Saya tidak merasa gagal',
                    'Saya kadang merasa kurang berhasil dibanding teman',
                    'Saya sering merasa gagal dalam perkuliahan',
                    'Saya merasa benar-benar gagal sebagai mahasiswa'
                ]],
                ['num' => 7, 'aspect' => 'Afektif', 'text' => 'Mengukur hilangnya kesenangan dalam aktivitas yang biasanya dinikmati.', 'options' => [
                    'Saya masih menikmati hal-hal yang biasa saya sukai',
                    'Saya agak kurang menikmati hal-hal tersebut',
                    'Saya hampir tidak menikmati hal-hal yang biasa saya sukai',
                    'Saya tidak menikmati apapun sama sekali'
                ]],
                ['num' => 8, 'aspect' => 'Afektif', 'text' => 'Mengukur tingkat gelisah atau agitasi yang dialami mahasiswa.', 'options' => [
                    'Saya tidak merasa lebih gelisah dari biasanya',
                    'Saya merasa sedikit lebih gelisah',
                    'Saya sangat gelisah sehingga sulit duduk tenang',
                    'Saya sangat gelisah sehingga harus terus bergerak'
                ]],
                ['num' => 9, 'aspect' => 'Kognitif', 'text' => 'Mengukur kemampuan mahasiswa dalam mengambil keputusan sehari-hari.', 'options' => [
                    'Saya dapat mengambil keputusan seperti biasa',
                    'Saya kadang menunda mengambil keputusan',
                    'Saya sangat sulit mengambil keputusan',
                    'Saya tidak dapat mengambil keputusan apapun'
                ]],
                ['num' => 10, 'aspect' => 'Kognitif', 'text' => 'Mengukur perasaan tidak berharga yang dialami mahasiswa.', 'options' => [
                    'Saya tidak merasa tidak berharga',
                    'Saya kadang merasa kurang berharga',
                    'Saya merasa jauh lebih tidak berharga dibanding orang lain',
                    'Saya merasa benar-benar tidak berharga'
                ]],
                ['num' => 11, 'aspect' => 'Somatik', 'text' => 'Mengukur tingkat energi dan kelelahan yang dialami mahasiswa.', 'options' => [
                    'Saya mempunyai energi yang cukup seperti biasa',
                    'Saya kekurangan energi dibanding biasanya',
                    'Saya tidak punya energi yang cukup untuk banyak hal',
                    'Saya tidak punya energi sama sekali untuk melakukan apapun'
                ]],
                ['num' => 12, 'aspect' => 'Somatik', 'text' => 'Mengukur perubahan pola tidur mahasiswa.', 'options' => [
                    'Pola tidur saya tidak berubah',
                    'Saya tidur sedikit lebih banyak/sedikit dari biasanya',
                    'Saya tidur jauh lebih banyak/sedikit dari biasanya',
                    'Saya hampir tidur sepanjang hari atau bangun 1-2 jam lebih awal'
                ]],
                ['num' => 13, 'aspect' => 'Kognitif', 'text' => 'Mengukur kemampuan konsentrasi mahasiswa dalam konteks akademik.', 'options' => [
                    'Saya dapat berkonsentrasi seperti biasa',
                    'Saya agak sulit berkonsentrasi saat kuliah',
                    'Saya sangat sulit berkonsentrasi pada apapun',
                    'Saya tidak dapat berkonsentrasi sama sekali'
                ]],
                ['num' => 14, 'aspect' => 'Somatik', 'text' => 'Mengukur perubahan nafsu makan mahasiswa.', 'options' => [
                    'Nafsu makan saya tidak berubah',
                    'Nafsu makan saya sedikit berkurang/bertambah',
                    'Nafsu makan saya sangat berkurang/bertambah',
                    'Saya tidak nafsu makan sama sekali'
                ]],
                ['num' => 15, 'aspect' => 'Afektif', 'text' => 'Mengukur tingkat iritabilitas atau mudah tersinggung.', 'options' => [
                    'Saya tidak lebih mudah tersinggung dari biasanya',
                    'Saya sedikit lebih mudah tersinggung',
                    'Saya sangat mudah tersinggung',
                    'Saya tersinggung sepanjang waktu'
                ]],
                ['num' => 16, 'aspect' => 'Afektif', 'text' => 'Mengukur hilangnya minat terhadap aktivitas dan orang lain.', 'options' => [
                    'Saya masih tertarik pada orang lain dan aktivitas',
                    'Saya agak kurang tertarik pada beberapa hal',
                    'Saya kehilangan sebagian besar minat pada hal-hal',
                    'Saya sama sekali tidak tertarik pada apapun'
                ]],
                ['num' => 17, 'aspect' => 'Kognitif', 'text' => 'Mengukur perasaan dihukum atau layak menerima hukuman.', 'options' => [
                    'Saya tidak merasa sedang dihukum',
                    'Saya merasa mungkin sedang dihukum',
                    'Saya berharap untuk dihukum',
                    'Saya merasa sedang dihukum'
                ]],
                ['num' => 18, 'aspect' => 'Kognitif', 'text' => 'Mengukur tingkat self-criticism atau mengkritik diri sendiri.', 'options' => [
                    'Saya tidak mengkritik diri sendiri secara berlebihan',
                    'Saya kadang mengkritik diri sendiri',
                    'Saya sering mengkritik diri sendiri atas semua kesalahan',
                    'Saya menyalahkan diri sendiri atas segala hal buruk'
                ]],
                ['num' => 19, 'aspect' => 'Somatik', 'text' => 'Mengukur perubahan berat badan yang dialami.', 'options' => [
                    'Berat badan saya tidak berubah',
                    'Berat badan saya sedikit berubah',
                    'Berat badan saya berubah cukup signifikan',
                    'Berat badan saya berubah drastis'
                ]],
                ['num' => 20, 'aspect' => 'Afektif', 'text' => 'Mengukur kecemasan tentang kesehatan fisik.', 'options' => [
                    'Saya tidak khawatir tentang kesehatan',
                    'Saya agak khawatir tentang kesehatan',
                    'Saya sangat khawatir tentang masalah kesehatan',
                    'Saya terlalu khawatir sehingga sulit memikirkan hal lain'
                ]],
                ['num' => 21, 'aspect' => 'Afektif', 'text' => 'Mengukur hilangnya minat terhadap aktivitas seksual/sosial.', 'options' => [
                    'Saya tidak merasakan perubahan pada minat saya',
                    'Saya agak kurang berminat dari biasanya',
                    'Saya jauh kurang berminat dari biasanya',
                    'Saya benar-benar kehilangan minat'
                ]],
            ];
            @endphp

            <!-- All questions (shown one at a time via JS) -->
            @foreach($questions as $q)
            <div class="question-card question-slide" id="question-{{ $q['num'] }}" style="{{ $q['num'] > 1 ? 'display:none;' : '' }}">
                <span class="question-number">
                    Pertanyaan {{ $q['num'] }}
                    <span class="question-aspect">{{ $q['aspect'] }}</span>
                </span>
                <p class="question-text">{{ $q['num'] }}. {{ $q['text'] }}</p>

                <div class="answer-options">
                    @foreach($q['options'] as $i => $option)
                    <div class="answer-option">
                        <input type="radio" name="answer_{{ $q['num'] }}" id="q{{ $q['num'] }}_a{{ $i }}" value="{{ $i }}" required>
                        <label for="q{{ $q['num'] }}_a{{ $i }}">
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
    const totalQuestions = 21;

    function updateProgress() {
        const answered = document.querySelectorAll('.answer-option input:checked').length;
        const pct = (answered / totalQuestions) * 100;
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('stepCounter').textContent = answered + '/' + totalQuestions;
    }

    function navigateQuestion(direction) {
        const current = document.getElementById('question-' + currentQuestion);
        const next = currentQuestion + direction;

        // Validate current question has answer before moving forward
        if (direction === 1) {
            const selectedAnswer = current.querySelector('input[type="radio"]:checked');
            if (!selectedAnswer) {
                current.style.animation = 'none';
                current.offsetHeight; // trigger reflow
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

        // Update nav buttons
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

    // Listen for answer changes to update progress
    document.querySelectorAll('.answer-option input').forEach(input => {
        input.addEventListener('change', updateProgress);
    });

    updateProgress();
</script>
@endpush
