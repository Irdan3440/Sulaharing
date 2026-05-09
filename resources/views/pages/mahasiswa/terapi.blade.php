@extends('layouts.app')

@section('title', 'Therapy CBT')
@section('page_title', 'Therapy CBT')
@section('page_subtitle', 'Guided Breathing & Pomodoro')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
<style>
/* 1️⃣ Breathing circle animation */
.breath-circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: var(--gradient-primary);
    margin: 2rem auto;
    animation: breathe 19s infinite;
}

/* 4-7-8 cycle: 4s enlarge, 7s hold, 8s shrink => 19s total */
@keyframes breathe {
    0%   { transform: scale(0.6); }
    21%  { transform: scale(1); }   /* 4s  (4/19≈21%) */
    58%  { transform: scale(1); }   /* hold for 7s (7/19≈37%, cumulative ≈58%) */
    100% { transform: scale(0.6); } /* 8s shrink (8/19≈42%) */
}

/* 2️⃣ Pomodoro timer */
.pomo-display {
    font-family: 'Inter', sans-serif;
    font-size: 2.5rem;
    text-align: center;
    margin: 1rem 0;
}
.pomo-btn {
    @apply px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 focus:outline-none;
}
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto p-4">

    {{-- ==================== Guided Breathing ==================== --}}
    <section class="mb-12 text-center bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">4-7-8 Breathing Exercise</h2>
        <p class="mb-2 text-gray-600">Tarik napas 4 detik → Tahan 7 detik → Hembuskan 8 detik</p>
        <div class="breath-circle flex items-center justify-center text-white text-xl font-medium"
             id="breathCircle">Inhale</div>
        <p class="mt-2 text-sm text-gray-500 font-medium" id="breathPhase">Phase: Inhale (4 s)</p>
    </section>

    {{-- ==================== Pomodoro Timer ==================== --}}
    <section class="text-center bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Pomodoro Study Timer</h2>
        <div class="pomo-display text-gray-800" id="pomodoroDisplay">25:00</div>
        <div class="space-x-2">
            <button class="pomo-btn bg-purple-500 hover:bg-purple-600" id="pomoStart">Start</button>
            <button class="pomo-btn bg-yellow-500 hover:bg-yellow-600" id="pomoPause">Pause</button>
            <button class="pomo-btn bg-gray-500 hover:bg-gray-600" id="pomoReset">Reset</button>
        </div>
        <p class="mt-4 text-sm font-medium text-gray-600" id="pomoStatus">Ready to focus</p>
    </section>
</div>
@endsection

@push('scripts')
<script>
// ---------- 1️⃣ Breathing animation ----------
const phases = [
    {name:'Inhale',   duration:4},
    {name:'Hold',     duration:7},
    {name:'Exhale',   duration:8}
];
let phaseIdx = 0;
let secLeft = phases[0].duration;
const circle = document.getElementById('breathCircle');
const phaseTxt = document.getElementById('breathPhase');

function updateBreathing() {
    const current = phases[phaseIdx];
    circle.textContent = current.name;
    phaseTxt.textContent = `Phase: ${current.name} (${secLeft}s)`;
    secLeft--;

    if (secLeft < 0) {
        // move to next phase
        phaseIdx = (phaseIdx + 1) % phases.length;
        secLeft = phases[phaseIdx].duration;
    }
}
setInterval(updateBreathing, 1000);

// ---------- 2️⃣ Pomodoro ----------
let pomoTimer;
let pomoSeconds = 25 * 60;   // 25 min
let isRunning = false;

const display = document.getElementById('pomodoroDisplay');
const statusTxt = document.getElementById('pomoStatus');

function formatTime(s){
    const m = Math.floor(s/60).toString().padStart(2,'0');
    const sec = (s%60).toString().padStart(2,'0');
    return `${m}:${sec}`;
}
function tick(){
    if (pomoSeconds <= 0) {
        clearInterval(pomoTimer);
        statusTxt.textContent = '🎉 Session selesai! Ambil istirahat.';
        isRunning = false;
        return;
    }
    pomoSeconds--;
    display.textContent = formatTime(pomoSeconds);
}
document.getElementById('pomoStart').onclick = ()=> {
    if (!isRunning) {
        pomoTimer = setInterval(tick, 1000);
        isRunning = true;
        statusTxt.textContent = '🧠 Fokus...';
    }
};
document.getElementById('pomoPause').onclick = ()=> {
    clearInterval(pomoTimer);
    isRunning = false;
    statusTxt.textContent = '⏸️ Dijeda';
};
document.getElementById('pomoReset').onclick = ()=> {
    clearInterval(pomoTimer);
    isRunning = false;
    pomoSeconds = 25*60;
    display.textContent = formatTime(pomoSeconds);
    statusTxt.textContent = 'Ready to focus';
};
</script>
@endpush
