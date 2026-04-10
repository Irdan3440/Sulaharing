<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Diagnosis SulaHaring</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #2563eb; }
        .subtitle { font-size: 14px; color: #6b7280; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .info-label { font-weight: bold; width: 150px; }
        .result-box { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; }
        .result-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #166534; }
        .result-score { font-size: 36px; font-weight: bold; color: #2563eb; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th, .details-table td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; }
        .details-table th { background: #f9fafb; font-weight: bold; }
        .footer { margin-top: 50px; border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-text">SulaHaring</div>
        <div class="subtitle">Sistem Pakar Diagnosis Depresi Mahasiswa Berbasis Certainty Factor</div>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama</td>
            <td>: {{ $consultation->is_guest ? $consultation->guest_name : ($consultation->user->name ?? '-') }}</td>
            <td class="info-label">Tanggal Diagnosis</td>
            <td>: {{ \Carbon\Carbon::parse($consultation->consulted_at)->translatedFormat('d F Y H:i') }}</td>
        </tr>
        <tr>
            <td class="info-label">Institusi / Fakultas</td>
            <td>: {{ $consultation->is_guest ? ($consultation->guest_institusi ?? '-') : ($consultation->user->fakultas ?? '-') }}</td>
            <td class="info-label">Status Pengguna</td>
            <td>: {{ $consultation->is_guest ? 'Tamu' : 'Mahasiswa Terdaftar' }}</td>
        </tr>
    </table>

    <div class="result-box">
        <div class="result-title">Hasil Diagnosis: {{ $consultation->disease->nama ?? 'Tidak Diketahui' }}</div>
        <div style="font-size: 16px; margin-bottom: 10px;">{{ $consultation->classification }}</div>
        <div class="result-score">Tingkat Keyakinan: {{ number_format($consultation->cf_percentage, 1) }}%</div>
        <div style="margin-top: 10px; font-size: 13px; color: #4b5563;">Skor BDI-II: {{ $consultation->total_score_bdi }}</div>
    </div>

    <h3 style="margin-top: 30px;">Saran Penanganan:</h3>
    <div style="background: #eff6ff; padding: 15px; border-radius: 8px; border: 1px solid #bfdbfe;">
        {!! nl2br(htmlspecialchars($consultation->saran)) !!}
    </div>

    <h3 style="margin-top: 30px;">Detail Indikator Gejala:</h3>
    <table class="details-table">
        <thead>
            <tr>
                <th width="10%">Kode</th>
                <th width="60%">Gejala (Indikator BDI-II)</th>
                <th width="15%">Pilihan Evaluasi</th>
                <th width="15%">CF User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultation->details as $detail)
            <tr>
                <td style="text-align: center;">{{ $detail->symptom->kode }}</td>
                <td>{{ $detail->symptom->nama }}</td>
                <td style="text-align: center;">{{ $detail->answer_score }}</td>
                <td style="text-align: center;">{{ number_format($detail->cf_user, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i:s') }} oleh SulaHaring System.<br>
        Dokumen ini dihasilkan secara otomatis dan merupakan hasil evaluasi awal. Untuk diagnosis pasti, harap berkonsultasi dengan profesional kesehatan mental (psikolog/psikiater).
    </div>
</body>
</html>
