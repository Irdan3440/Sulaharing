@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page_title', 'Pengguna')
@section('page_subtitle', 'Kelola akun mahasiswa, pakar, dan admin')

@section('sidebar')
<div class="sidebar-section"><div class="sidebar-section-title">Administrasi</div><ul>
    <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}" class="sidebar-link active"><i data-lucide="users"></i> Pengguna</a></li>
    <li><a href="{{ route('admin.reports') }}" class="sidebar-link"><i data-lucide="bar-chart-3"></i> Laporan</a></li>
</ul></div>
@endsection

@push('styles')
<style>
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th, .data-table td { padding:0.75rem 1rem; text-align:left; border-bottom:1px solid var(--border-color); font-size:var(--text-sm); }
    .data-table th { font-weight:700; color:var(--text-secondary); font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.05em; background:var(--gray-50); }
    .data-table tbody tr:hover { background:var(--primary-50); }
    .filter-bar { display:flex; gap:var(--space-3); margin-bottom:var(--space-5); flex-wrap:wrap; align-items:center; }
    .badge-role { padding:3px 10px; border-radius:var(--radius-full); font-weight:700; font-size:10px; text-transform:uppercase; }
    .badge-role.mahasiswa { background:rgba(59,130,246,0.1); color:var(--primary-600); }
    .badge-role.pakar { background:rgba(139,92,246,0.1); color:var(--violet-600); }
    .badge-role.admin { background:rgba(239,68,68,0.1); color:var(--danger-600); }
    .btn-actions { display:flex; gap:var(--space-2); }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.active { display:flex; }
    .modal-card { background:white; border-radius:var(--radius-2xl); padding:var(--space-8); width:100%; max-width:520px; max-height:90vh; overflow-y:auto; }
</style>
@endpush

@section('content')
<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">Daftar Pengguna ({{ $users->total() }})</h5>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('addUserModal').classList.add('active')">
            <i data-lucide="user-plus" style="width:16px;height:16px"></i> Tambah
        </button>
    </div>

    <form method="GET" class="filter-bar">
        <input name="search" class="form-input" placeholder="Cari nama/NIM/email..." value="{{ request('search') }}" style="max-width:250px">
        <select name="role" class="form-input" style="max-width:140px"><option value="">Semua Role</option><option value="mahasiswa" {{ request('role')=='mahasiswa'?'selected':'' }}>Mahasiswa</option><option value="pakar" {{ request('role')=='pakar'?'selected':'' }}>Pakar</option><option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option></select>
        <button class="btn btn-sm btn-secondary" type="submit"><i data-lucide="search" style="width:14px;height:14px"></i> Filter</button>
    </form>

    <div style="overflow-x:auto">
    <table class="data-table">
        <thead><tr><th>Nama</th><th>NIM</th><th>Email</th><th>Fakultas</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @foreach($users as $u)
            <tr>
                <td><strong>{{ $u->name }}</strong></td>
                <td>{{ $u->nim ?? '-' }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->fakultas ?? '-' }}</td>
                <td><span class="badge-role {{ $u->role }}">{{ $u->role }}</span></td>
                <td>{!! $u->is_active ? '<span style="color:var(--success-600);font-weight:600">●</span> Aktif' : '<span style="color:var(--gray-400)">●</span> Nonaktif' !!}</td>
                <td>
                    <div class="btn-actions">
                        <button class="btn btn-sm btn-secondary" onclick="editUser({{ json_encode($u->only(['id','name','email','nim','fakultas','role'])) }})">
                            <i data-lucide="pencil" style="width:14px;height:14px"></i>
                        </button>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i data-lucide="trash-2" style="width:14px;height:14px"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div style="margin-top:var(--space-4)">{{ $users->withQueryString()->links() }}</div>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="addUserModal">
    <div class="modal-card">
        <h4 style="margin-bottom:var(--space-4)">Tambah Pengguna</h4>
        <form method="POST" action="{{ route('admin.users.store') }}">@csrf
            <div class="form-group"><label class="form-label">Nama</label><input name="name" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Email</label><input name="email" type="email" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Password</label><input name="password" type="password" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Role</label>
                <select name="role" class="form-input"><option value="mahasiswa">Mahasiswa</option><option value="pakar">Pakar</option><option value="admin">Admin</option></select></div>
            <div class="form-group"><label class="form-label">NIM</label><input name="nim" class="form-input"></div>
            <div class="form-group"><label class="form-label">Fakultas</label><input name="fakultas" class="form-input"></div>
            <div style="display:flex;gap:var(--space-3);justify-content:flex-end">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addUserModal').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal-overlay" id="editUserModal">
    <div class="modal-card">
        <h4 style="margin-bottom:var(--space-4)">Edit Pengguna</h4>
        <form method="POST" id="editUserForm">@csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama</label><input name="name" id="euName" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Email</label><input name="email" id="euEmail" type="email" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Password <small>(kosongkan jika tidak diubah)</small></label><input name="password" type="password" class="form-input"></div>
            <div class="form-group"><label class="form-label">Role</label>
                <select name="role" id="euRole" class="form-input"><option value="mahasiswa">Mahasiswa</option><option value="pakar">Pakar</option><option value="admin">Admin</option></select></div>
            <div class="form-group"><label class="form-label">NIM</label><input name="nim" id="euNim" class="form-input"></div>
            <div class="form-group"><label class="form-label">Fakultas</label><input name="fakultas" id="euFakultas" class="form-input"></div>
            <div style="display:flex;gap:var(--space-3);justify-content:flex-end">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editUserModal').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editUser(u) {
    document.getElementById('editUserForm').action = '/admin/users/' + u.id;
    document.getElementById('euName').value = u.name;
    document.getElementById('euEmail').value = u.email;
    document.getElementById('euNim').value = u.nim || '';
    document.getElementById('euFakultas').value = u.fakultas || '';
    document.getElementById('euRole').value = u.role;
    document.getElementById('editUserModal').classList.add('active');
}
</script>
@endpush
