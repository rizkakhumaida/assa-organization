<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengajuan Kerja Sama</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 30px;
        }
        h3 {
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <h3>Daftar Pengajuan Kerja Sama</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Organisasi</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Jenis Kerjasama</th>
                <th>Ringkasan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proposals as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->organization_name }}</td>
                <td>{{ $p->contact_email }}</td>
                <td>{{ $p->contact_phone ?? '-' }}</td>
                <td>{{ $p->cooperation_type ?? '-' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($p->proposal_summary, 50) }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
