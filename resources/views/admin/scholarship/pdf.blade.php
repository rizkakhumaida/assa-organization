<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pengajuan Beasiswa</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #555; padding: 6px 8px; text-align: left; }
        th { background: #efefef; }
        .badge { padding: 2px 6px; border-radius: 4px; border: 1px solid #444; }
    </style>
</head>
<body>
    <h2>Data Pengajuan Beasiswa</h2>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Nama Pendaftar</th>
            <th>Campus</th>
            <th>Program Studi</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($scholarships as $i => $s)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $s->nama_pendaftar }}</td>
                <td>{{ $s->campus ?? '-' }}</td>
                <td>{{ $s->program_studi ?? '-' }}</td>
                <td><span class="badge">{{ ucfirst($s->status) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
