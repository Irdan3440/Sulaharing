@extends('layouts.guest')

@section('title', 'SulaHaring — Kesehatan Mental, Prioritas Utamamu')
@section('meta_description', 'Sistem pakar skrining depresi mahasiswa dengan integrasi IoT smartwatch. Deteksi dini depresi menggunakan metode Certainty Factor.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endpush

@section('content')
<!-- Navbar -->
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="navbar-inner">
            <a href="{{ url('/') }}" class="navbar-logo">
                <svg width="40" height="40" viewBox="0 0 48 48" fill="none">
                    <defs>
                        <linearGradient id="navLogoGrad" x1="0" y1="0" x2="48" y2="48">
                            <stop offset="0%" stop-color="#8b5cf6"/>
                            <stop offset="100%" stop-color="#3b82f6"/>
                        </linearGradient>
                    </defs>
                    <path d="M24 4C18.5 4 14 8.5 14 14c0 3.5 1.8 6.6 4.5 8.4L12 35c-1 2 .5 4 2.5 4h19c2 0 3.5-2 2.5-4l-6.5-12.6C32.2 20.6 34 17.5 34 14c0-5.5-4.5-10-10-10z" fill="url(#navLogoGrad)"/>
                    <path d="M20 16c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <circle cx="24" cy="20" r="2" fill="white"/>
                </svg>
                <span class="navbar-logo-text">Sula<span>Haring</span></span>
            </a>

            <ul class="navbar-links">
                <li><a href="#features" class="active">Fitur</a></li>
                <li><a href="#cara-kerja">Cara Kerja</a></li>
                <li><a href="#artikel">Artikel</a></li>
                <li><a href="#tentang">Tentang</a></li>
            </ul>

            <div class="navbar-actions">
                <a href="{{ url('/login') }}" class="btn btn-secondary btn-sm">Masuk</a>
                <a href="{{ url('/register') }}" class="btn btn-primary btn-sm">Daftar</a>
                <button class="navbar-mobile-toggle" onclick="openMobileNav()" aria-label="Menu">
                    <i data-lucide="menu" style="width:24px;height:24px"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Nav -->
<div class="mobile-nav-overlay" id="mobileOverlay" onclick="closeMobileNav()"></div>
<div class="mobile-nav" id="mobileNav">
    <button class="mobile-nav-close" onclick="closeMobileNav()" aria-label="Close menu">
        <i data-lucide="x" style="width:24px;height:24px"></i>
    </button>
    <ul class="mobile-nav-links">
        <li><a href="#features" onclick="closeMobileNav()">Fitur</a></li>
        <li><a href="#cara-kerja" onclick="closeMobileNav()">Cara Kerja</a></li>
        <li><a href="#artikel" onclick="closeMobileNav()">Artikel</a></li>
        <li><a href="#tentang" onclick="closeMobileNav()">Tentang</a></li>
    </ul>
    <div class="mobile-nav-actions">
        <a href="{{ url('/login') }}" class="btn btn-secondary btn-block">Masuk</a>
        <a href="{{ url('/register') }}" class="btn btn-primary btn-block">Daftar</a>
    </div>
</div>

<!-- Hero Section -->
<section class="hero" id="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-badge">
                    <i data-lucide="shield-check" style="width:16px;height:16px"></i>
                    Sistem Pakar Berbasis AI & IoT
                </div>

                <h1 class="hero-title">
                    Kesehatan <span class="highlight">Mentalmu</span>,<br>
                    Prioritas Utamamu
                </h1>

                <p class="hero-subtitle">
                    Integrasi web diagnosis & IoT smartwatch untuk deteksi dini depresi. 
                    Menggunakan metode <strong>Certainty Factor</strong> yang tervalidasi secara klinis.
                </p>

                <div class="hero-actions">
                    <a href="{{ url('/diagnosa') }}" class="btn btn-primary btn-lg" id="btn-mulai-diagnosa">
                        <i data-lucide="stethoscope" style="width:20px;height:20px"></i>
                        Mulai Diagnosa
                    </a>
                    <a href="#cara-kerja" class="btn btn-secondary btn-lg">
                        <i data-lucide="play-circle" style="width:20px;height:20px"></i>
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-value">21</div>
                        <div class="hero-stat-label">Indikator BDI-II</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">4</div>
                        <div class="hero-stat-label">Tingkat Klasifikasi</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">IoT</div>
                        <div class="hero-stat-label">Smartwatch Terintegrasi</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-illustration">
                    <div class="brain-visual">
                        <svg viewBox="0 0 120 120" fill="none">
                            <!-- Stylized brain/heart icon -->
                            <path d="M60 20c-16 0-28 12-28 28 0 10 5 18.5 12.5 23.5L32 95c-1.5 3 1 6 4 6h48c3 0 5.5-3 4-6L75.5 71.5C83 66.5 88 58 88 48c0-16-12-28-28-28z" fill="url(#brainGrad)" opacity="0.2"/>
                            <path d="M60 30c-10 0-18 8-18 18s8 18 18 18 18-8 18-18-8-18-18-18z" stroke="url(#brainGrad)" stroke-width="2" fill="none"/>
                            <path d="M52 44c0-4.4 3.6-8 8-8s8 3.6 8 8" stroke="#3b82f6" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                            <circle cx="60" cy="52" r="3" fill="#3b82f6"/>
                            <!-- Heartbeat line -->
                            <path d="M30 60h15l5-12 8 24 6-18 5 6h21" stroke="#06b6d4" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" opacity="0.6"/>
                            <defs>
                                <linearGradient id="brainGrad" x1="32" y1="20" x2="88" y2="101">
                                    <stop offset="0%" stop-color="#8b5cf6"/>
                                    <stop offset="100%" stop-color="#3b82f6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <!-- Floating cards -->
                    <div class="hero-card-float card-1">
                        <div class="card-icon blue">
                            <i data-lucide="heart-pulse" style="width:18px;height:18px"></i>
                        </div>
                        <div class="card-label">Heart Rate</div>
                        <div class="card-value">72 BPM — Normal</div>
                    </div>

                    <div class="hero-card-float card-2">
                        <div class="card-icon green">
                            <i data-lucide="brain" style="width:18px;height:18px"></i>
                        </div>
                        <div class="card-label">CF Score</div>
                        <div class="card-value">35% — Ringan</div>
                    </div>

                    <div class="hero-card-float card-3">
                        <div class="card-icon violet">
                            <i data-lucide="activity" style="width:18px;height:18px"></i>
                        </div>
                        <div class="card-label">HRV</div>
                        <div class="card-value">45ms — Baik</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" id="features">
    <div class="container">
        <div class="section-header">
            <span class="section-label">
                <i data-lucide="sparkles" style="width:14px;height:14px"></i>
                Fitur Unggulan
            </span>
            <h2 class="section-title">Teknologi Cerdas untuk<br>Kesehatan Mental</h2>
            <p class="section-subtitle">
                Kombinasi sistem pakar & IoT untuk pemantauan kesehatan mental yang komprehensif dan real-time.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card animate-fade-in-up">
                <div class="feature-icon blue">
                    <i data-lucide="clipboard-check" style="width:28px;height:28px"></i>
                </div>
                <h4 class="feature-title">Kuesioner BDI-II</h4>
                <p class="feature-desc">
                    21 indikator tervalidasi yang mengukur aspek kognitif, afektif, dan somatik untuk diagnosis depresi yang akurat.
                </p>
            </div>

            <div class="feature-card animate-fade-in-up delay-200">
                <div class="feature-icon violet">
                    <i data-lucide="cpu" style="width:28px;height:28px"></i>
                </div>
                <h4 class="feature-title">Engine Certainty Factor</h4>
                <p class="feature-desc">
                    Kalkulasi Measure of Belief & Disbelief dari setiap gejala untuk menghasilkan persentase kepastian diagnosis.
                </p>
            </div>

            <div class="feature-card animate-fade-in-up delay-400">
                <div class="feature-icon cyan">
                    <i data-lucide="watch" style="width:28px;height:28px"></i>
                </div>
                <h4 class="feature-title">IoT Smartwatch</h4>
                <p class="feature-desc">
                    Sensor MAX30102 memantau detak jantung & HRV secara real-time via MQTT untuk deteksi anomali fisiologis.
                </p>
            </div>

            <div class="feature-card animate-fade-in-up delay-100">
                <div class="feature-icon green">
                    <i data-lucide="trending-up" style="width:28px;height:28px"></i>
                </div>
                <h4 class="feature-title">Mood Tracker</h4>
                <p class="feature-desc">
                    Pantau pola kesehatan mental harian dengan grafik mood interaktif dan streak pengisian untuk motivasi.
                </p>
            </div>

            <div class="feature-card animate-fade-in-up delay-300">
                <div class="feature-icon blue">
                    <i data-lucide="file-text" style="width:28px;height:28px"></i>
                </div>
                <h4 class="feature-title">Rekam Medis PDF</h4>
                <p class="feature-desc">
                    Unduh hasil diagnosis lengkap dalam format PDF sebagai rekam medis mandiri. Data tersimpan aman di sistem.
                </p>
            </div>

            <div class="feature-card animate-fade-in-up delay-500">
                <div class="feature-icon violet">
                    <i data-lucide="shield" style="width:28px;height:28px"></i>
                </div>
                <h4 class="feature-title">Dikelola Pakar</h4>
                <p class="feature-desc">
                    Basis pengetahuan diperbarui langsung oleh psikolog kampus. Bobot gejala selalu sesuai standar klinis terbaru.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section how-it-works" id="cara-kerja">
    <div class="container">
        <div class="section-header">
            <span class="section-label">
                <i data-lucide="route" style="width:14px;height:14px"></i>
                Cara Kerja
            </span>
            <h2 class="section-title">Empat Langkah Mudah</h2>
            <p class="section-subtitle">
                Proses skrining yang simpel namun komprehensif untuk kesehatan mentalmu.
            </p>
        </div>

        <div class="steps-container">
            <div class="step-card animate-fade-in-up">
                <div class="step-number s1">1</div>
                <h5 class="step-title">Isi Identitas</h5>
                <p class="step-desc">Lengkapi data diri singkat untuk personalisasi hasil diagnosis.</p>
            </div>

            <div class="step-card animate-fade-in-up delay-200">
                <div class="step-number s2">2</div>
                <h5 class="step-title">Jawab Kuesioner</h5>
                <p class="step-desc">Jawab 21 pertanyaan BDI-II yang mengukur tingkat gejala depresi.</p>
            </div>

            <div class="step-card animate-fade-in-up delay-400">
                <div class="step-number s3">3</div>
                <h5 class="step-title">Analisis Sistem</h5>
                <p class="step-desc">Engine CF menghitung persentase kepastian berdasarkan basis aturan pakar.</p>
            </div>

            <div class="step-card animate-fade-in-up delay-600">
                <div class="step-number s4">4</div>
                <h5 class="step-title">Terima Hasil</h5>
                <p class="step-desc">Lihat klasifikasi depresi, saran penanganan, dan rujukan bantuan.</p>
            </div>
        </div>
    </div>
</section>

<!-- Articles Section -->
<section class="section" id="artikel" style="background:white;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">
                <i data-lucide="book-open" style="width:14px;height:14px"></i>
                Artikel Edukasi
            </span>
            <h2 class="section-title">Baca & Pelajari</h2>
            <p class="section-subtitle">
                Informasi terpercaya seputar kesehatan mental dari sumber yang terverifikasi.
            </p>
        </div>

        <div class="articles-grid">
            <div class="article-card animate-fade-in-up">
                <div class="article-image" style="background:linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%); display:flex; align-items:center; justify-content:center;">
                    <i data-lucide="brain" style="width:64px;height:64px;color:#3b82f6;opacity:0.5"></i>
                </div>
                <div class="article-body">
                    <div class="article-category">Edukasi</div>
                    <h5 class="article-title">Mengenal Depresi: Lebih dari Sekadar Sedih</h5>
                    <p class="article-excerpt">Depresi merupakan gangguan mood yang mempengaruhi cara seseorang berpikir, merasa, dan menjalani aktivitas sehari-hari.</p>
                    <div class="article-meta">
                        <span>5 menit baca</span>
                        <span>Tim SulaHaring</span>
                    </div>
                </div>
            </div>

            <div class="article-card animate-fade-in-up delay-200">
                <div class="article-image" style="background:linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%); display:flex; align-items:center; justify-content:center;">
                    <i data-lucide="heart-pulse" style="width:64px;height:64px;color:#8b5cf6;opacity:0.5"></i>
                </div>
                <div class="article-body">
                    <div class="article-category">Tips</div>
                    <h5 class="article-title">5 Cara Menjaga Kesehatan Mental Saat Kuliah</h5>
                    <p class="article-excerpt">Tekanan akademis dapat mempengaruhi kesehatan mental. Simak tips praktis untuk menjaga keseimbangan hidup di kampus.</p>
                    <div class="article-meta">
                        <span>4 menit baca</span>
                        <span>Tim SulaHaring</span>
                    </div>
                </div>
            </div>

            <div class="article-card animate-fade-in-up delay-400">
                <div class="article-image" style="background:linear-gradient(135deg, #ccfbf1 0%, #cffafe 100%); display:flex; align-items:center; justify-content:center;">
                    <i data-lucide="users" style="width:64px;height:64px;color:#06b6d4;opacity:0.5"></i>
                </div>
                <div class="article-body">
                    <div class="article-category">Berita</div>
                    <h5 class="article-title">Pentingnya Deteksi Dini Depresi pada Mahasiswa</h5>
                    <p class="article-excerpt">Penelitian menunjukkan bahwa deteksi dini dan intervensi tepat waktu dapat mencegah depresi berkembang lebih parah.</p>
                    <div class="article-meta">
                        <span>6 menit baca</span>
                        <span>Tim SulaHaring</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" id="tentang">
    <div class="container">
        <div class="cta-content">
            <h2>Mulai Perjalanan Menuju<br>Kesehatan Mental Yang Lebih Baik</h2>
            <p>Skrining gratis, aman, dan tervalidasi secara klinis. Tidak perlu akun untuk mencoba.</p>
            <div class="cta-actions">
                <a href="{{ url('/diagnosa') }}" class="btn btn-white btn-lg">
                    <i data-lucide="stethoscope" style="width:20px;height:20px"></i>
                    Mulai Diagnosa Sekarang
                </a>
                <a href="{{ url('/register') }}" class="btn btn-ghost-white btn-lg">
                    <i data-lucide="user-plus" style="width:20px;height:20px"></i>
                    Buat Akun Gratis
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="navbar-logo" style="margin-bottom:0">
                    <svg width="36" height="36" viewBox="0 0 48 48" fill="none">
                        <defs><linearGradient id="footerGrad" x1="0" y1="0" x2="48" y2="48"><stop offset="0%" stop-color="#8b5cf6"/><stop offset="100%" stop-color="#3b82f6"/></linearGradient></defs>
                        <path d="M24 4C18.5 4 14 8.5 14 14c0 3.5 1.8 6.6 4.5 8.4L12 35c-1 2 .5 4 2.5 4h19c2 0 3.5-2 2.5-4l-6.5-12.6C32.2 20.6 34 17.5 34 14c0-5.5-4.5-10-10-10z" fill="url(#footerGrad)"/>
                        <path d="M20 16c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <circle cx="24" cy="20" r="2" fill="white"/>
                    </svg>
                    <span class="navbar-logo-text" style="color:white">Sula<span>Haring</span></span>
                </a>
                <p>Sistem pakar berbasis web untuk skrining depresi mahasiswa menggunakan metode Certainty Factor yang terintegrasi dengan IoT smartwatch.</p>
            </div>

            <div>
                <h6 class="footer-heading">Platform</h6>
                <ul class="footer-links">
                    <li><a href="{{ url('/diagnosa') }}">Mulai Diagnosa</a></li>
                    <li><a href="{{ url('/register') }}">Daftar</a></li>
                    <li><a href="{{ url('/login') }}">Masuk</a></li>
                </ul>
            </div>

            <div>
                <h6 class="footer-heading">Informasi</h6>
                <ul class="footer-links">
                    <li><a href="#tentang">Tentang Kami</a></li>
                    <li><a href="#artikel">Artikel</a></li>
                    <li><a href="#cara-kerja">Cara Kerja</a></li>
                </ul>
            </div>

            <div>
                <h6 class="footer-heading">Bantuan</h6>
                <ul class="footer-links">
                    <li><a href="#">Hubungi Kami</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Into The Light — 119 ext 8</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} SulaHaring. Hak cipta dilindungi.</span>
            <div class="footer-social">
                <a href="#" aria-label="Instagram"><i data-lucide="instagram" style="width:18px;height:18px"></i></a>
                <a href="#" aria-label="Twitter"><i data-lucide="twitter" style="width:18px;height:18px"></i></a>
                <a href="#" aria-label="Github"><i data-lucide="github" style="width:18px;height:18px"></i></a>
            </div>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Mobile nav
    function openMobileNav() {
        document.getElementById('mobileNav').classList.add('open');
        document.getElementById('mobileOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        document.getElementById('mobileNav').classList.remove('open');
        document.getElementById('mobileOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-down').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
</script>
@endpush
