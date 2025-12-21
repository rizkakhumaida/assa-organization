@extends('layouts.app')

@section('content')
<style>
    body{
        background: radial-gradient(1200px 600px at 10% 0%, #dbeafe 0%, transparent 60%),
                    radial-gradient(900px 500px at 90% 20%, #e0f2fe 0%, transparent 55%),
                    linear-gradient(135deg, #f1f5f9, #eef2ff);
        font-family: 'Poppins', sans-serif;
    }

    .page-header{
        background: linear-gradient(90deg, #1e40af, #2563eb);
        border-radius: 16px;
        padding: 18px 22px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(37,99,235,.30);
    }
    .page-header h4{ margin:0; font-weight:800; letter-spacing:.2px; }
    .page-header small{ opacity:.9; font-size:.85rem; }

    .glass{
        background: rgba(255,255,255,.88);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,.45);
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(2,6,23,.08);
    }
    .stat-card{
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid rgba(148,163,184,.25);
        background: rgba(255,255,255,.85);
    }
    .stat-title{ font-size:.85rem; color:#475569; margin:0; }
    .stat-value{ font-size:1.4rem; font-weight:800; margin:0; color:#0f172a; }
    .stat-chip{
        font-size:.75rem;
        padding:.35rem .6rem;
        border-radius: 999px;
        background: #eef2ff;
        color:#3730a3;
        font-weight: 600;
    }

    .table-wrap{ border-radius: 16px; overflow: hidden; }
    .table thead th{
        font-size:.85rem;
        letter-spacing:.2px;
        color:#0f172a;
        background: #eff6ff;
        border-bottom: 1px solid #dbeafe;
        padding: 14px 14px;
        white-space: nowrap;
    }
    .table tbody td{
        padding: 14px 14px;
        border-color: #eef2f7;
        vertical-align: middle;
        color:#0f172a;
    }
    .table tbody tr:hover{
        background: #f8fafc;
    }
    .muted{ color:#64748b; font-size:.9rem; }

    .badge-soft{
        padding:.45rem .65rem;
        border-radius: 999px;
        font-weight: 700;
        font-size:.78rem;
        letter-spacing:.2px;
        border: 1px solid transparent;
    }
    .st-approved{ background: rgba(34,197,94,.12); color:#166534; border-color: rgba(34,197,94,.25); }
    .st-rejected{ background: rgba(239,68,68,.12); color:#7f1d1d; border-color: rgba(239,68,68,.25); }
    .st-hold{ background: rgba(245,158,11,.14); color:#7c2d12; border-color: rgba(245,158,11,.28); }
    .st-pending{ background: rgba(100,116,139,.12); color:#0f172a; border-color: rgba(100,116,139,.22); }

    .control{
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: .65rem .85rem;
        background: rgba(255,255,255,.9);
    }
    .control:focus{
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 .2rem rgba(59,130,246,.15);
    }

    .btn-icon{
        width: 38px; height: 38px;
        display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 12px;
    }
</style>

@php
    // NORMALISASI STATUS: agar "Approved", "approved", "APPROVED" semuanya dianggap sama
    $norm = fn($s) => strtolower(trim($s ?? 'pending'));

    $countApproved = $applications->filter(fn($a) => $norm($a->status) === 'approved')->count();
    $countRejected = $applications->filter(fn($a) => $norm($a->status) === 'rejected')->count();
    $countHold     = $applications->filter(fn($a) => $norm($a->status) === 'on hold')->count();
    $countPending  = $applications->filter(fn($a) => !in_array($norm($a->status), ['approved','rejected','on hold'], true))->count();
@endphp

<div class="container py-5">

    <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </div>
            <div>
                <h4>Daftar Pendaftar Beasiswa</h4>
                <small>Kelola pengajuan, tinjau dokumen, dan lakukan verifikasi status</small>
            </div>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm fw-semibold">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success glass border-0 mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Disetujui</p><span class="stat-chip">Approved</span>
                </div>
                <p class="stat-value">{{ $countApproved }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Ditolak</p><span class="stat-chip">Rejected</span>
                </div>
                <p class="stat-value">{{ $countRejected }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Ditunda</p><span class="stat-chip">On Hold</span>
                </div>
                <p class="stat-value">{{ $countHold }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Pending</p><span class="stat-chip">Pending</span>
                </div>
                <p class="stat-value">{{ $countPending }}</p>
            </div>
        </div>
    </div>

    <div class="glass p-3 mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0" style="border-radius:14px 0 0 14px;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input id="searchInput" type="text" class="form-control control border-0"
                           placeholder="Cari nama / email / kampus..." style="border-radius:0 14px 14px 0;">
                </div>
                <div class="muted mt-1">Pencarian hanya di halaman ini.</div>
            </div>

            <div class="col-md-3">
                <select id="statusFilter" class="form-select control">
                    <option value="">Semua Status</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                    <option value="on hold">Ditunda</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <div class="col-md-4 d-flex justify-content-md-end gap-2">
                <button id="resetFilter" type="button" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-circle"></i> Reset
                </button>
                <span class="btn btn-primary btn-sm disabled">
                    Total: {{ $applications->total() }}
                </span>
            </div>
        </div>
    </div>

    <div class="glass">
        <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="fw-bold text-dark">
                <i class="bi bi-table me-1"></i> Data Pendaftar
            </div>
            <div class="muted">
                Menampilkan {{ $applications->count() }} dari {{ $applications->total() }} data
            </div>
        </div>

        <div class="table-responsive table-wrap">
            <table class="table align-middle mb-0" id="appsTable">
                <thead>
                    <tr>
                        <th style="width:70px;">No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Kampus</th>
                        <th>Status</th>
                        <th class="text-end" style="width:170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $index => $item)
                        @php
                            $statusKey = strtolower(trim($item->status ?? 'pending'));

                            $isPending = !in_array($statusKey, ['approved','rejected','on hold'], true);

                            $badgeClass = $statusKey === 'approved' ? 'st-approved'
                                : ($statusKey === 'rejected' ? 'st-rejected'
                                : ($statusKey === 'on hold' ? 'st-hold' : 'st-pending'));

                            $statusLabel = $statusKey === 'approved' ? 'Disetujui'
                                : ($statusKey === 'rejected' ? 'Ditolak'
                                : ($statusKey === 'on hold' ? 'Ditunda' : 'Pending'));

                            $filterValue = $isPending ? 'pending' : $statusKey;
                        @endphp

                        <tr data-status="{{ $filterValue }}">
                            <td class="muted">{{ $applications->firstItem() + $index }}</td>

                            <td>
                                <div class="fw-semibold">{{ $item->full_name }}</div>
                                <div class="muted">ID: #{{ $item->id }}</div>
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $item->email }}</div>
                                <div class="muted">Terdaftar: {{ optional($item->created_at)->format('d M Y') }}</div>
                            </td>

                            <td class="muted">{{ $item->campus ?? '-' }}</td>

                            <td>
                                <span class="badge-soft {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- AKSI: Detail, Edit, Hapus --}}
                            <td class="text-end">
                                <a href="{{ route('admin.scholarship.show', $item->id) }}"
                                   class="btn btn-primary btn-sm btn-icon me-1"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.scholarship.edit', $item->id) }}"
                                   class="btn btn-warning btn-sm btn-icon me-1"
                                   title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.scholarship.destroy', $item->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                                            class="btn btn-danger btn-sm btn-icon"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <div class="fw-semibold mb-1">Belum ada data pendaftar beasiswa</div>
                                <div class="muted">Data akan muncul setelah user melakukan pengajuan.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex justify-content-center">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<script>
    (function () {
        const searchInput  = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn     = document.getElementById('resetFilter');
        const rows         = document.querySelectorAll('#appsTable tbody tr');

        function applyFilters(){
            const q = (searchInput.value || '').toLowerCase().trim();
            const st = (statusFilter.value || '').toLowerCase().trim();

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

                const matchText = !q || text.includes(q);
                const matchStatus = !st || rowStatus === st;

                row.style.display = (matchText && matchStatus) ? '' : 'none';
            });
        }

        searchInput?.addEventListener('input', applyFilters);
        statusFilter?.addEventListener('change', applyFilters);

        resetBtn?.addEventListener('click', () => {
            searchInput.value = '';
            statusFilter.value = '';
            applyFilters();
        });
    })();
</script>
@endsection
