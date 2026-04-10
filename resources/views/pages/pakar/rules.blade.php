@extends('layouts.app')
@section('title', 'Aturan Certainty Factor')
@section('page_title', 'Aturan CF')
@section('page_subtitle', 'Kelola bobot Measure of Belief (MB) dan Measure of Disbelief (MD)')

@section('sidebar')
<div class="sidebar-section"><div class="sidebar-section-title">Menu Utama</div><ul>
    <li><a href="{{ route('pakar.dashboard') }}" class="sidebar-link"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route('pakar.diseases') }}" class="sidebar-link"><i data-lucide="heart-crack"></i> Penyakit</a></li>
    <li><a href="{{ route('pakar.symptoms') }}" class="sidebar-link"><i data-lucide="stethoscope"></i> Gejala</a></li>
    <li><a href="{{ route('pakar.rules') }}" class="sidebar-link active"><i data-lucide="settings-2"></i> Aturan CF</a></li>
</ul></div>
@endsection

@push('styles')
<style>
    .rules-grid { display:grid; gap:var(--space-4); }
    .rule-row { display:grid; grid-template-columns:80px 1.5fr 1fr 100px 100px 80px auto; gap:var(--space-3); align-items:center; padding:0.75rem 1rem; background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-xl); font-size:var(--text-sm); }
    .rule-row:hover { border-color:var(--primary-300); }
    .rule-header { font-weight:700; color:var(--text-secondary); font-size:var(--text-xs); text-transform:uppercase; background:transparent; border:none; padding:0.5rem 1rem; }
    .rule-input { width:80px; padding:0.4rem 0.5rem; border:1px solid var(--border-color); border-radius:var(--radius-md); text-align:center; font-weight:600; font-size:var(--text-sm); }
    .rule-input:focus { border-color:var(--primary-500); outline:none; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    .cf-value { font-weight:800; font-family:var(--font-heading); }
    .cf-value.positive { color:var(--success-600); }
    .cf-value.negative { color:var(--danger-600); }
    .badge-kode { padding:2px 8px; background:var(--primary-50); color:var(--primary-700); border-radius:var(--radius-full); font-weight:700; font-size:var(--text-xs); }
    .badge-disease { padding:2px 8px; border-radius:var(--radius-full); font-weight:600; font-size:10px; }
    .badge-disease.d1 { background:rgba(34,197,94,0.1); color:var(--success-600); }
    .badge-disease.d2 { background:rgba(245,158,11,0.1); color:#b45309; }
    .badge-disease.d3 { background:rgba(249,115,22,0.1); color:#ea580c; }
    .badge-disease.d4 { background:rgba(239,68,68,0.1); color:var(--danger-600); }
    .filter-group { display:flex; gap:var(--space-3); margin-bottom:var(--space-5); flex-wrap:wrap; }
</style>
@endpush

@section('content')
<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">Aturan CF ({{ $rules->count() }} aturan)</h5>
    </div>

    <div class="filter-group">
        <select id="filterDisease" class="form-input" style="max-width:200px" onchange="filterRules()">
            <option value="">Semua Penyakit</option>
            @foreach($diseases as $disease)
                <option value="{{ $disease->id }}">{{ $disease->kode }} — {{ $disease->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="rule-row rule-header">
        <div>Gejala</div><div>Nama</div><div>Penyakit</div><div>MB</div><div>MD</div><div>CF</div><div>Aksi</div>
    </div>

    <div class="rules-grid" id="rulesGrid">
    @foreach($rules as $rule)
        <form method="POST" action="{{ route('pakar.rules.update', $rule) }}" class="rule-row" data-disease="{{ $rule->disease_id }}">
            @csrf @method('PUT')
            <div><span class="badge-kode">{{ $rule->symptom->kode }}</span></div>
            <div>{{ $rule->symptom->nama }}</div>
            <div>
                @php $dIdx = $rule->disease->kode === 'D001' ? 'd1' : ($rule->disease->kode === 'D002' ? 'd2' : ($rule->disease->kode === 'D003' ? 'd3' : 'd4')); @endphp
                <span class="badge-disease {{ $dIdx }}">{{ $rule->disease->kode }}</span>
            </div>
            <div><input type="number" name="mb" value="{{ $rule->mb }}" step="0.01" min="0" max="1" class="rule-input" onchange="updateCf(this)"></div>
            <div><input type="number" name="md" value="{{ $rule->md }}" step="0.01" min="0" max="1" class="rule-input" onchange="updateCf(this)"></div>
            <div class="cf-value {{ ($rule->mb - $rule->md) >= 0 ? 'positive' : 'negative' }}" id="cf-{{ $rule->id }}">{{ number_format($rule->mb - $rule->md, 2) }}</div>
            <div><button type="submit" class="btn btn-sm btn-primary"><i data-lucide="save" style="width:14px;height:14px"></i></button></div>
        </form>
    @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateCf(input) {
    const form = input.closest('form');
    const mb = parseFloat(form.querySelector('[name=mb]').value) || 0;
    const md = parseFloat(form.querySelector('[name=md]').value) || 0;
    const cf = (mb - md).toFixed(2);
    const cfEl = form.querySelector('.cf-value');
    cfEl.textContent = cf;
    cfEl.className = 'cf-value ' + (parseFloat(cf) >= 0 ? 'positive' : 'negative');
}

function filterRules() {
    const val = document.getElementById('filterDisease').value;
    document.querySelectorAll('.rule-row[data-disease]').forEach(row => {
        row.style.display = (!val || row.dataset.disease === val) ? '' : 'none';
    });
}
</script>
@endpush
