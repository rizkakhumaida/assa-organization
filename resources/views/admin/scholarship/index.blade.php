@extends('layouts.app')

@section('content')
<style>
    /* 🌌 GLOBAL THEME */
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 50%, #f1f5f9 100%);
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }

    /* ✨ HEADER TITLE */
    .gradient-title {
        background: linear-gradient(90deg, #2563eb, #0ea5e9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 10px rgba(37,99,235,0.3);
    }

    /* 💎 CARD GLASS EFFECT */
    .card-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(18px);
        border-radius: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        overflow: hidden;
    }
    .card-glass:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
    }

    /* 🌈 TABLE STYLING */
    .table thead {
        background: linear-gradient(90deg, #2563eb, #0ea5e9);
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .table tbody tr {
        transition: all 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: rgba(37, 99, 235, 0.08);
        transform: scale(1.005);
    }

    /* 🏷️ BADGE STYLE */
    .badge {
        font-size: 0.85rem;
        padding: 0.45em 0.75em;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .badge.bg-success { background: linear-gradient(90deg, #16a34a, #22c55e); }
    .badge.bg-danger { background: linear-gradient(90deg, #dc2626, #ef4444); }
    .badge.bg-warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); color: #000; }
    .badge.bg-secondary { background: linear-gradient(90deg, #6b7280, #9ca3af); }

    /* 🎯 BUTTON STYLE */
    .btn-custom {
        border-radius: 10px;
        transition: all 0.25s ease-in-out;
        font-weight: 500;
    }
    .btn-custom:hover {
        transform: translateY(-2px);
    }
    .btn-outline-primary {
        border-color: #2563eb;
        color: #2563eb;
    }
    .btn-outline-primary:hover {
        background: linear-gradient(90deg, #2563eb, #0ea5e9);
        color: white;
        border: none;
        box-shadow: 0 0 10px rgba(37,99,235,0.4);
    }
    .btn-info {
        background: linear-gradient(90deg, #0ea5e9, #38bdf8);
        border: none;
    }
    .btn-danger {
        background: linear-gradient(90deg, #dc2626, #ef4444);
        border: none;
    }
    .btn-outline-danger {
        border-color: #ef4444;
        color: #dc2626;
    }
    .btn-outline-danger:hover {
        background: linear-gradient(90deg, #dc2626, #ef4444);
        color: white;
        border: none;
    }
    .btn-outline-success {
        border-color: #16a34a;
        color: #16a34a;
    }
    .btn-outline-success:hover {
        background: linear-gradient(90deg, #16a34a, #22c55e);
        color: white;
        border: none;
    }

    /* 🌀 ANIMATION */
    .fade-in {
        animation: fadeIn 0.7s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 📱 PAGINATION */
    .page-link {
        border-radius: 10px !important;
        color: #2563eb !important;
        transition: 0.3s;
    }
    .page-link:hover {
        background: rgba(37,99,235,0.1);
    }
    .page-item.active .page-link {
        background: linear-gradient(90deg, #2563eb, #0ea5e9) !important;
        border: none;
        color: #fff !important;
        box-shadow: 0 0 10px rgba(37,99,235,0.4);
    }

    /* 🌙 DARK MODE TOGGLE */
    .dark-mode {
        background: linear-gradient(135deg, #0f172a, #1e293b, #334155) !important;
        color: #e2e8f0;
    }
    .dark-mode .card-glass {
        background: rgba(30, 41, 59, 0.85);
        color: #e2e8f0;
    }
    .dark-mode .table thead {
        background: linear-gradient(90deg, #1d4ed8, #2563eb);
    }
    .dark-mode .table tbody tr:hover {
        background: rgba(59,130,246,0.15);
    }
</style>

<div class="container py-5 fade-in">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold gradient-title mb-0">
            <i class="bi bi-mortarboard-fill me-2"></i> Daftar Pendaftar Beasiswa
        </h2>
        <div class="d-flex gap-2">
            <button id="themeToggle" class="btn btn-outline-primary btn-custom px-3">
                <i class="bi bi-moon-stars me-1"></i> Tema
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-custom px-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- 🔽 TOMBOL EXPORT --}}
    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="{{ route('admin.scholarship.export.pdf') }}"
           class="btn btn-outline-danger btn-custom px-3">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
        </a>
        <a href="{{ route('admin.scholarship.export.excel') }}"
           class="btn btn-outline-success btn-custom px-3">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
        </a>
    </div>

    {{-- ALERT SUKSES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABEL --}}
    <div class="card card-glass border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pendaftar</th>
                            <th>Kampus</th>
                            <th>Program Studi</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $index => $item)
                            <tr>
                                <td>{{ $applications->firstItem() + $index }}</td>
                                <td>{{ $item->user->name ?? $item->nama_pendaftar ?? '-' }}</td>
                                <td>{{ $item->campus ?? '-' }}</td>
                                <td>{{ $item->program_studi ?? '-' }}</td>

                                {{-- Dokumen --}}
                                <td>
                                    @if ($item->dokumen)
                                        <a href="{{ asset('storage/' . $item->dokumen) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary btn-custom px-3 py-1"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Dokumen">
                                           <i class="bi bi-file-earmark-text"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @switch($item->status)
                                        @case('approved')
                                            <span class="badge bg-success">Disetujui</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                        @case('on hold')
                                            <span class="badge bg-warning text-dark">Ditunda</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Pending</span>
                                    @endswitch
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.scholarship.show', $item->id) }}"
                                       class="btn btn-sm btn-info text-white btn-custom me-1"
                                       data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.scholarship.destroy', $item->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                                            class="btn btn-sm btn-danger btn-custom"
                                            data-bs-toggle="tooltip" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-emoji-frown fs-3"></i><br>
                                    Belum ada data pendaftar beasiswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap tooltip
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // Toggle dark mode
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const icon = themeToggle.querySelector('i');
        icon.classList.toggle('bi-sun');
        icon.classList.toggle('bi-moon-stars');
    });
</script>
@endsection
