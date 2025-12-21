@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        background: #ffffff;
        font-size: 11px;
        color: #111;
    }

    .page-header {
        border-bottom: 2px solid #1e40af;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }

    .page-header h4 {
        margin: 0;
        font-weight: 700;
        color: #1e40af;
    }

    .page-header small {
        font-size: 10px;
        color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
    }

    th, td {
        border: 1px solid #444;
        padding: 6px 8px;
    }

    th {
        background: #f1f1f1;
        font-weight: 700;
        text-align: center;
    }

    td {
        vertical-align: top;
    }

    .text-center {
        text-align: center;
    }

    .badge {
        padding: 3px 6px;
        border-radius: 6px;
        font-size: 10px;
        border: 1px solid #333;
        text-transform: uppercase;
    }

    .footer {
        margin-top: 20px;
        font-size: 9px;
        text-align: right;
        color: #555;
    }
</style>

<div class="container py-3">

    {{-- HEADER --}}
    <div class="page-header">
        <h4>Laporan Pengajuan Beasiswa</h4>
        <small>ASSA Organization — {{ now()->format('d M Y') }}</small>
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="22%">Nama Pendaftar</th>
                <th width="22%">Email</th>
                <th width="18%">Kampus</th>
                <th width="14%">Tanggal</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($scholarships as $i => $s)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $s->full_name ?? ($s->user->name ?? '-') }}</td>
                    <td>{{ $s->email ?? '-' }}</td>
                    <td>{{ $s->campus ?? '-' }}</td>
                    <td class="text-center">
                        {{ $s->created_at ? $s->created_at->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="badge">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Belum ada data pengajuan beasiswa.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }}
    </div>

</div>
@endsection
