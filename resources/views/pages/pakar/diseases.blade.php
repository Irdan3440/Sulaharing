@extends('layouts.app')
@section('title', 'Manajemen Penyakit')
@section('page_title', 'Manajemen Penyakit')
@section('page_subtitle', 'Kelola klasifikasi depresi dan saran penanganan')

@section('sidebar')
<div class="sidebar-section">
    <div class="sidebar-section-title">Menu Utama</div>
    <ul>
        <li><a href="{{ route('pakar.dashboard') }}" class="sidebar-link"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('pakar.diseases') }}" class="sidebar-link active"><i data-lucide="heart-crack"></i> Penyakit</a></li>
        <li><a href="{{ route('pakar.symptoms') }}" class="sidebar-link"><i data-lucide="stethoscope"></i> Gejala</a></li>
        <li><a href="{{ route('pakar.rules') }}" class="sidebar-link"><i data-lucide="settings-2"></i> Aturan CF</a></li>
    </ul>
</div>
@endsection

@push('styles')
<style>
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th, .data-table td { padding:0.75rem 1rem; text-align:left; border-bottom:1px solid var(--border-color); font-size:var(--text-sm); }
    .data-table th { font-weight:700; color:var(--text-secondary); font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.05em; background:var(--gray-50); }
    .data-table tbody tr:hover { background:var(--primary-50); }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.active { display:flex; }
    .modal-card { background:white; border-radius:var(--radius-2xl); padding:var(--space-8); width:100%; max-width:560px; max-height:90vh; overflow-y:auto; }
    .btn-actions { display:flex; gap:var(--space-2); }
    .badge-kode { padding:2px 8px; background:var(--primary-50); color:var(--primary-700); border-radius:var(--radius-full); font-weight:700; font-size:var(--text-xs); }
</style>
@endpush

@section('content')
<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">Daftar Penyakit ({{ $diseases->count() }})</h5>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('addModal').classList.add('active')">
            <i data-lucide="plus" style="width:16px;height:16px"></i> Tambah
        </button>
    </div>
    <table class="data-table">
        <thead><tr><th>Kode</th><th>Nama</th><th>Rentang Skor</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach($diseases as $d)
            <tr>
                <td><span class="badge-kode">{{ $d->kode }}</span></td>
                <td><strong>{{ $d->nama }}</strong></td>
                <td>{{ $d->rentang_skor }}</td>
                <td style="max-width:300px;">{{ Str::limit($d->deskripsi, 80) }}</td>
                <td>
                    <div class="btn-actions">
                        <button class="btn btn-sm btn-secondary" onclick="editDisease({{ $d->id }}, '{{ addslashes($d->nama) }}', '{{ addslashes($d->deskripsi) }}', '{{ $d->rentang_skor }}', '{{ addslashes($d->saran_penanganan) }}', '{{ addslashes($d->rujukan_helpdesk) }}')">
                            <i data-lucide="pencil" style="width:14px;height:14px"></i>
                        </button>
                        <form method="POST" action="{{ route('pakar.diseases.destroy', $d) }}" onsubmit="return confirm('Hapus penyakit ini?')">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i data-lucide="trash-2" style="width:14px;height:14px"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal-card">
        <h4 style="margin-bottom:var(--space-4)">Tambah Penyakit</h4>
        <form method="POST" action="{{ route('pakar.diseases.store') }}">@csrf
            <div class="form-group"><label class="form-label">Kode</label><input name="kode" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Nama</label><input name="nama" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-input" rows="3" required></textarea></div>
            <div class="form-group"><label class="form-label">Rentang Skor</label><input name="rentang_skor" class="form-input" placeholder="0-13" required></div>
            <div class="form-group"><label class="form-label">Saran Penanganan</label><textarea name="saran_penanganan" class="form-input" rows="3"></textarea></div>
            <div class="form-group"><label class="form-label">Rujukan Helpdesk</label><input name="rujukan_helpdesk" class="form-input"></div>
            <div style="display:flex;gap:var(--space-3);justify-content:flex-end">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addModal').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-card">
        <h4 style="margin-bottom:var(--space-4)">Edit Penyakit</h4>
        <form method="POST" id="editForm">@csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama</label><input name="nama" id="editNama" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" id="editDeskripsi" class="form-input" rows="3" required></textarea></div>
            <div class="form-group"><label class="form-label">Rentang Skor</label><input name="rentang_skor" id="editRentang" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Saran Penanganan</label><textarea name="saran_penanganan" id="editSaran" class="form-input" rows="3"></textarea></div>
            <div class="form-group"><label class="form-label">Rujukan Helpdesk</label><input name="rujukan_helpdesk" id="editRujukan" class="form-input"></div>
            <div style="display:flex;gap:var(--space-3);justify-content:flex-end">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editModal').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editDisease(id, nama, deskripsi, rentang, saran, rujukan) {
    document.getElementById('editForm').action = '/pakar/diseases/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editRentang').value = rentang;
    document.getElementById('editSaran').value = saran;
    document.getElementById('editRujukan').value = rujukan;
    document.getElementById('editModal').classList.add('active');
}
</script>
@endpush
