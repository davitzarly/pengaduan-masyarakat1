<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f2f2f2; text-align: left; }
        .meta { margin-bottom: 12px; }
    </style>
</head>
<body>
    <h1>Laporan Pengaduan</h1>
    <div class="meta">
        <div>Tanggal: {{ now()->format('Y-m-d H:i') }}</div>
        @if(!empty($filters))
            <div>Filter: {{ json_encode($filters) }}</div>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Prediksi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pengaduans as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->judul }}</td>
                <td>{{ optional($p->kategori)->nama ?? '-' }}</td>
                <td>{{ optional($p->user)->name ?? 'Guest' }}</td>
                <td>{{ $p->status }}</td>
                <td>
                    {{ $p->kategori_prediksi ?? '-' }}
                    @if($p->kategori_prediksi_skor !== null)
                        ({{ number_format($p->kategori_prediksi_skor * 100, 1) }}%)
                    @endif
                </td>
                <td>{{ $p->created_at ? $p->created_at->format('Y-m-d') : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
