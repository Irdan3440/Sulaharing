@extends('layouts.guest')

@section('title', 'Hasil Konsultasi — SulaHaring')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/questionnaire.css') }}">
@endpush

@section('content')
<div class="result-page">
    <div class="result-container">
        <div class="result-card">
            <!-- Logo -->
            <div class="result-logo">
                <img src="{{ asset('images/logo-sulaharing.png') }}" alt="SulaHaring" style="width:48px;height:48px;object-fit:contain">
            </div>

            <h2 class="result-title">Hasil Konsultasi</h2>
            <p class="result-method">Hasil Diagnosis — metode: Certainty Factor (CF) only</p>

            <!-- CF Score -->
            @php
                $cfPercentage = $cf_percentage ?? 35.0;
                $classification = $classification ?? 'Ringan';
                $classSlug = strtolower($classification);
                $classSlug = $classSlug === 'minimal' ? 'minimal' : ($classSlug === 'ringan' ? 'ringan' : ($classSlug === 'sedang' ? 'sedang' : 'berat'));
                $classLabel = $classification ?? 'Depresi Ringan';
                $classEnglish = $classSlug === 'minimal' ? 'Minimal Depression' : ($classSlug === 'ringan' ? 'Mild Depression' : ($classSlug === 'sedang' ? 'Moderate Depression' : 'Severe Depression'));
            @endphp

            <div class="cf-score-display">
                <div class="cf-score-label">
                    Indeks<br>Keyakinan Sistem
                </div>
                <div>
                    <span class="cf-score-value {{ $classSlug }}">{{ number_format($cfPercentage, 1) }}<span class="cf-score-unit">%</span></span>
                </div>
            </div>

            <!-- Classification -->
            <div class="result-classification">
                <span class="classification-badge {{ $classSlug }}">
                    @if($classSlug === 'minimal')
                        <i data-lucide="smile" style="width:22px;height:22px"></i>
                    @elseif($classSlug === 'ringan')
                        <i data-lucide="meh" style="width:22px;height:22px"></i>
                    @elseif($classSlug === 'sedang')
                        <i data-lucide="frown" style="width:22px;height:22px"></i>
                    @else
                        <i data-lucide="alert-triangle" style="width:22px;height:22px"></i>
                    @endif
                    Depresi {{ $classLabel }} ({{ $classEnglish }})
                </span>
            </div>

            <!-- Description -->
            <div class="result-description">
                @if($classSlug === 'minimal')
                    Hasil tes menunjukkan kondisi mental yang sehat. Anda tidak menunjukkan gejala depresi yang signifikan. Tetap jaga pola hidup sehat dan lakukan aktivitas yang menyenangkan untuk mempertahankan kondisi ini.
                @elseif($classSlug === 'ringan')
                    Menunjukkan adanya gejala depresi ringan. Biasanya muncul perasaan sedih, kehilangan minat, atau sulit tidur, tapi masih bisa menjalankan aktivitas harian. Perlu diwaspadai agar tidak berkembang menjadi depresi sedang, disarankan menjaga pola hidup sehat dan mencari dukungan sosial. Intervensi sangat penting untuk mencegah perkembangan kondisi yang lebih luas.
                @elseif($classSlug === 'sedang')
                    Menunjukkan gejala depresi sedang yang memerlukan perhatian serius. Gejala mulai mengganggu aktivitas akademik dan sosial. Disarankan untuk segera berkonsultasi dengan psikolog atau konselor kampus.
                @else
                    <strong>Peringatan:</strong> Hasil menunjukkan gejala depresi berat. Kondisi ini memerlukan penanganan profesional segera. Disarankan untuk menghubungi psikolog, psikiater, atau layanan krisis kesehatan mental.
                @endif
            </div>

            <!-- Recommendations -->
            <div class="result-recommendations">
                <h4>
                    <i data-lucide="lightbulb" style="width:22px;height:22px"></i>
                    Rekomendasi Tindakan
                </h4>
                <ul>
                    @if($classSlug === 'minimal')
                        <li>Tetap jaga pola tidur teratur (7-9 jam per malam)</li>
                        <li>Lakukan olahraga ringan minimal 30 menit setiap hari</li>
                        <li>Pertahankan hubungan sosial yang positif</li>
                        <li>Lakukan tes berkala untuk memantau kondisi</li>
                    @elseif($classSlug === 'ringan')
                        <li>Bangun dan tidur pada jam yang sama setiap hari</li>
                        <li>Makan teratur — meski tidak lapar, makan camilan bergizi</li>
                        <li>Terapkan Teknik Pomodoro yang dimodifikasi (15 menit belajar, 5 menit istirahat) untuk konsentrasi terbatas</li>
                        <li>Jadwalkan waktu aktifitas pada jam-jam puncak energi (biasanya pagi hari untuk penderita depresi)</li>
                        <li>Pertimbangkan untuk berkonsultasi dengan konselor kampus</li>
                    @elseif($classSlug === 'sedang')
                        <li>Segera hubungi layanan konseling kampus</li>
                        <li>Bicarakan kondisi Anda dengan orang yang dipercaya</li>
                        <li>Hindari isolasi — tetap terhubung dengan teman atau keluarga</li>
                        <li>Pertimbangkan untuk konsultasi dengan psikolog profesional</li>
                        <li>Jangan ragu untuk meminta perpanjangan waktu tugas kepada dosen</li>
                    @else
                        <li><strong>Segera hubungi layanan krisis: Into The Light — 119 ext 8</strong></li>
                        <li>Hubungi psikolog atau psikiater profesional</li>
                        <li>Jangan tinggal sendirian — beritahu orang terdekat tentang kondisi Anda</li>
                        <li>Pertimbangkan cuti akademik jika diperlukan</li>
                        <li>Unit Pelayanan Konseling kampus dapat membantu koordinasi penanganan</li>
                    @endif
                </ul>
            </div>

            <!-- Actions -->
            <div class="result-actions">
                @if(!($is_guest ?? true))
                    {{-- Mahasiswa: Download PDF + Lihat Riwayat --}}
                    <a href="{{ route('riwayat.pdf', $consultation->id) }}" class="btn btn-primary btn-lg">
                        <i data-lucide="download" style="width:20px;height:20px"></i>
                        Download PDF
                    </a>
                    <a href="{{ url('/riwayat') }}" class="btn btn-secondary btn-lg">
                        <i data-lucide="history" style="width:20px;height:20px"></i>
                        Lihat Riwayat
                    </a>
                @else
                    {{-- Guest: Download PDF + Buat Akun + Ulangi --}}
                    <a href="{{ route('guest.pdf', $consultation->id) }}" class="btn btn-primary btn-lg">
                        <i data-lucide="download" style="width:20px;height:20px"></i>
                        Download PDF
                    </a>
                    <a href="{{ url('/register') }}" class="btn btn-secondary btn-lg">
                        <i data-lucide="user-plus" style="width:20px;height:20px"></i>
                        Simpan & Buat Akun
                    </a>
                    <a href="{{ url('/diagnosa') }}" class="btn btn-outline btn-lg">
                        <i data-lucide="refresh-ccw" style="width:20px;height:20px"></i>
                        Ulangi Diagnosa
                    </a>
                @endif
                <a href="{{ url('/') }}" class="btn btn-outline btn-lg">
                    <i data-lucide="home" style="width:20px;height:20px"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Disclaimer -->
            <div class="result-disclaimer">
                <i data-lucide="alert-triangle" style="width:16px;height:16px;flex-shrink:0;display:inline"></i>
                <strong>Penting:</strong> Hasil skrining ini bersifat <strong>informatif</strong> dan bukan diagnosis medis resmi. 
                Jika Anda merasa memerlukan bantuan, segera hubungi psikolog atau konselor profesional. 
                Untuk keadaan darurat: <strong>Into The Light — 119 ext 8</strong>.
            </div>
        </div>
    </div>
</div>
@endsection
