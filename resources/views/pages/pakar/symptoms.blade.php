@extends('layouts.app')
@section('title', 'Manajemen Gejala')
@section('page_title', 'Manajemen Gejala')
@section('page_subtitle', 'Kelola 21 indikator BDI-II')

@section('sidebar')
<div class="sidebar-section"><div class="sidebar-section-title">Menu Utama</div><ul>
    <li><a href="{{ route('pakar.dashboard') }}" class="sidebar-link"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route('pakar.diseases') }}" class="sidebar-link"><i data-lucide="heart-crack"></i> Penyakit</a></li>
    <li><a href="{{ route('pakar.symptoms') }}" class="sidebar-link active"><i data-lucide="stethoscope"></i> Gejala</a></li>
    <li><a href="{{ route('pakar.rules') }}" class="sidebar-link"><i data-lucide="settings-2"></i> Aturan CF</a></li>
</ul></div>
@endsection

@push('styles')
<style>
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th, .data-table td { padding:0.75rem 1rem; text-align:left; border-bottom:1px solid var(--border-color); font-size:var(--text-sm); }
    .data-table th { font-weight:700; color:var(--text-secondary); font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.05em; background:var(--gray-50); }
    .data-table tbody tr:hover { background:var(--primary-50); }
    .badge-aspek { padding:2px 8px; border-radius:var(--radius-full); font-weight:700; font-size:10px; text-transform:uppercase; }
    .badge-aspek.kognitif { background:rgba(59,130,246,0.1); color:var(--primary-600); }
    .badge-aspek.afektif { background:rgba(139,92,246,0.1); color:var(--violet-600); }
    .badge-aspek.somatik { background:rgba(6,182,212,0.1); color:var(--accent-600); }
    .badge-kode { padding:2px 8px; background:var(--primary-50); color:var(--primary-700); border-radius:var(--radius-full); font-weight:700; font-size:var(--text-xs); }
    .btn-actions { display:flex; gap:var(--space-2); }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.active { display:flex; }
    .modal-card { background:white; border-radius:var(--radius-2xl); padding:var(--space-8); width:100%; max-width:600px; max-height:90vh; overflow-y:auto; }
</style>
@endpush

@section('content')
<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">Daftar Gejala ({{ $symptoms->count() }})</h5>
    </div>
    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr><th>Kode</th><th>Nama</th><th>Aspek</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach($symptoms as $s)
            <tr>
                <td><span class="badge-kode">{{ $s->kode }}</span></td>
                <td><strong>{{ $s->nama }}</strong></td>
                <td><span class="badge-aspek {{ $s->aspek }}">{{ $s->aspek }}</span></td>
                <td style="max-width:300px;">{{ Str::limit($s->deskripsi, 60) }}</td>
                <td>
                    <div class="btn-actions">
                        <button class="btn btn-sm btn-secondary" onclick="viewSymptom({{ json_encode($s) }})">
                            <i data-lucide="eye" style="width:14px;height:14px"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

<!-- View Modal -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-card">
        <h4 style="margin-bottom:var(--space-4)" id="viewTitle">Detail Gejala</h4>
        <div id="viewContent"></div>
        <div style="margin-top:var(--space-4);text-align:right">
            <button class="btn btn-secondary" onclick="document.getElementById('viewModal').classList.remove('active')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewSymptom(s) {
    let html = `
        <p><strong>Kode:</strong> ${s.kode}</p>
        <p><strong>Nama:</strong> ${s.nama}</p>
        <p><strong>Aspek:</strong> ${s.aspek}</p>
        <p><strong>Deskripsi:</strong> ${s.deskripsi}</p>
        <p style="margin-top:var(--space-4)"><strong>Pilihan Jawaban:</strong></p>
        <ol style="padding-left:var(--space-5)">
    `;
    s.pilihan_jawaban.forEach((opt, i) => { html += `<li style="margin-bottom:4px;color:var(--text-secondary)">(${i}) ${opt}</li>`; });
    html += '</ol>';
    document.getElementById('viewContent').innerHTML = html;
    document.getElementById('viewTitle').textContent = s.kode + ' — ' + s.nama;
    document.getElementById('viewModal').classList.add('active');
}
</script>
@endpush
