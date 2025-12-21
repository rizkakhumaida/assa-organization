<!DOCTYPE html>
<html>
<head>
    <title>Daftar Prestasi Peserta</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        h3 { text-align: center; margin-bottom: 10px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        tr:nth-child(even) { background-color: #fafafa; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h3>Daftar Prestasi Peserta</h3>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Peserta</th>
                <th>Asal</th>
                <th>Prestasi</th>
                <th>Tingkat</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="text-center">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achievements as $index => $a)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $a->peserta }}</td>
                    <td>{{ $a->asal_sekolah ?? '-' }}</td>
                    <td>{{ $a->prestasi }}</td>
                    <td>{{ $a->tingkat ?? '-' }}</td>
                    <td>{{ $a->kategori ?? '-' }}</td>
                    <td>{{ $a->tahun ? $a->tahun->format('d-m-Y') : '-' }}</td>
                    <td>{{ $a->status ?? 'Pending' }}</td>
                    <td class="text-center">{{ $a->poin ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data prestasi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
