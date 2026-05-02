<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Skrining Depresi Kampus</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #6a54a4; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #6a54a4; }
        .subtitle { font-size: 14px; color: #6b7280; }
        .filter-info { margin-bottom: 20px; background: #f3f4f6; padding: 10px; border-radius: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        .data-table th { background: #f9fafb; font-weight: bold; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .footer { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-text">SulaHaring</div>
        <div class="subtitle">Laporan Hasil Skrining Depresi Mahasiswa Berbasis Certainty Factor</div>
    </div>

    <div class="filter-info">
        <strong>Filter Laporan:</strong><br>
        Fakultas: {{ $request->fakultas ?? 'Semua Fakultas' }} | 
        Klasifikasi: {{ $request->classification ?? 'Semua' }} | 
        Periode: {{ $request->from ? \Carbon\Carbon::parse($request->from)->format('d/m/Y') : 'Awal' }} s/d {{ $request->to ? \Carbon\Carbon::parse($request->to)->format('d/m/Y') : 'Sekarang' }}
        <br>
        <strong>Total Data:</strong> {{ $consultations->count() }} Konsultasi
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Nama Mahasiswa</th>
                <th width="10%">NIM</th>
                <th width="15%">Fakultas</th>
                <th width="12%">Penyakit Indikasi</th>
                <th width="15%">Klasifikasi</th>
                <th width="10%">CF (%)</th>
                <th width="5%">BDI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consultations as $index => $c)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($c->consulted_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $c->is_guest ? ($c->guest_name ?? 'Tamu') : ($c->user->name ?? '-') }}</td>
                <td>{{ $c->is_guest ? '-' : ($c->user->nim ?? '-') }}</td>
                <td>{{ $c->is_guest ? ($c->guest_institusi ?? '-') : ($c->user->fakultas ?? '-') }}</td>
                <td>{{ $c->disease->nama ?? '-' }}</td>
                <td>{{ $c->classification }}</td>
                <td style="text-align: center;">{{ number_format($c->cf_percentage, 1) }}</td>
                <td style="text-align: center;">{{ $c->total_score_bdi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data konsultasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i:s') }} oleh Admin SulaHaring System.
    </div>
</body>
</html>
