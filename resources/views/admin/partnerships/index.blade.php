@extends('layouts.app')

@section('content')
@php
    $norm = fn($s) => strtolower(trim($s ?? 'submitted'));

    $countSubmitted = $proposals->getCollection()->filter(fn($p) => $norm($p->status) === 'submitted')->count();
    $countReview    = $proposals->getCollection()->filter(fn($p) => $norm($p->status) === 'review')->count();
    $countPending   = $proposals->getCollection()->filter(fn($p) => $norm($p->status) === 'pending')->count();
    $countApproved  = $proposals->getCollection()->filter(fn($p) => $norm($p->status) === 'approved')->count();
    $countRejected  = $proposals->getCollection()->filter(fn($p) => $norm($p->status) === 'rejected')->count();
    $countOnhold    = $proposals->getCollection()->filter(fn($p) => $norm($p->status) === 'onhold')->count();
@endphp

<style>
    body{
        background:
            radial-gradient(1100px 520px at 15% 0%, #dbeafe 0%, transparent 60%),
            radial-gradient(900px 520px at 85% 10%, #e0f2fe 0%, transparent 55%),
            linear-gradient(135deg, #f1f5f9, #eef2ff);
        font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .page-header{
        background: linear-gradient(90deg, #1e40af, #2563eb);
        border-radius: 16px;
        padding: 18px 22px;
        color: #fff;
        box-shadow: 0 14px 34px rgba(37,99,235,.30);
    }
    .page-header h3{ margin:0; font-weight:800; letter-spacing:.2px; }
    .page-header small{ opacity:.9; font-size:.9rem; }

    .glass{
        background: rgba(255,255,255,.86);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,.45);
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(2,6,23,.08);
    }

    .stat-card{
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid rgba(148,163,184,.22);
        background: rgba(255,255,255,.88);
        box-shadow: 0 10px 25px rgba(2,6,23,.05);
    }
    .stat-title{ font-size:.82rem; color:#64748b; margin:0; }
    .stat-value{ font-size:1.45rem; font-weight:900; margin:0; color:#0f172a; }
    .stat-chip{
        font-size:.74rem;
        padding:.32rem .62rem;
        border-radius: 999px;
        background: #eef2ff;
        color:#3730a3;
        font-weight: 700;
        border:1px solid rgba(99,102,241,.18);
    }

    .control{
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: .65rem .85rem;
        background: rgba(255,255,255,.92);
    }
    .control:focus{
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 .2rem rgba(59,130,246,.15);
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
    .table tbody tr:hover{ background: #f8fafc; }

    .muted{ color:#64748b; font-size:.9rem; }
    .xsmall{ font-size:.74rem; color:#64748b; }

    .badge-soft{
        padding:.45rem .7rem;
        border-radius: 999px;
        font-weight: 800;
        font-size:.78rem;
        letter-spacing:.2px;
        border: 1px solid transparent;
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        white-space:nowrap;
    }
    .st-approved{ background: rgba(34,197,94,.12); color:#166534; border-color: rgba(34,197,94,.22); }
    .st-rejected{ background: rgba(239,68,68,.12); color:#7f1d1d; border-color: rgba(239,68,68,.22); }
    .st-pending{ background: rgba(245,158,11,.14); color:#7c2d12; border-color: rgba(245,158,11,.24); }
    .st-review{ background: rgba(59,130,246,.12); color:#1e40af; border-color: rgba(59,130,246,.22); }
    .st-submitted{ background: rgba(100,116,139,.12); color:#0f172a; border-color: rgba(100,116,139,.20); }
    .st-onhold{ background: rgba(99,102,241,.12); color:#3730a3; border-color: rgba(99,102,241,.20); }

    .badge-type{
        background: #e8f1ff;
        color: #1b57f2;
        border: 1px solid rgba(27,87,242,.14);
        font-weight: 800;
    }

    .btn-icon{
        width: 38px; height: 38px;
        display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 12px;
    }
    .btn-soft{
        background: rgba(255,255,255,.9);
        border: 1px solid #e2e8f0;
        color:#0f172a;
        box-shadow: 0 10px 22px rgba(2,6,23,.06);
    }
    .btn-soft:hover{ filter: brightness(.98); }

    .btn-download{
        background: #16a34a;
        border: none;
        color:#fff;
        font-weight: 700;
    }
    .btn-download:hover{ filter: brightness(.95); color:#fff; }

    .link-danger.btn-link-danger{ text-decoration:none; }
    .link-danger.btn-link-danger:hover{ text-decoration: underline; }

    .divider{
        height:1px;
        background: linear-gradient(90deg, transparent, rgba(15,23,42,.12), transparent);
    }
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                <i class="bi bi-clipboard2-check fs-5"></i>
            </div>
            <div>
                <h3>Daftar Pengajuan Kerjasama</h3>
                <small>Kelola proposal, unduh dokumen, dan lakukan verifikasi status</small>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.partnerships.export.pdf') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="bi bi-filetype-pdf me-1"></i> Export PDF
            </a>
            <a href="{{ route('admin.partnerships.export.excel') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="bi bi-filetype-xlsx me-1"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="alert alert-success glass border-0 mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger glass border-0 mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    {{-- STATS (berdasarkan data di halaman ini) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Submitted</p><span class="stat-chip">submitted</span>
                </div>
                <p class="stat-value">{{ $countSubmitted }}</p>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Review</p><span class="stat-chip">review</span>
                </div>
                <p class="stat-value">{{ $countReview }}</p>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Pending</p><span class="stat-chip">pending</span>
                </div>
                <p class="stat-value">{{ $countPending }}</p>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Approved</p><span class="stat-chip">approved</span>
                </div>
                <p class="stat-value">{{ $countApproved }}</p>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Rejected</p><span class="stat-chip">rejected</span>
                </div>
                <p class="stat-value">{{ $countRejected }}</p>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">On Hold</p><span class="stat-chip">onhold</span>
                </div>
                <p class="stat-value">{{ $countOnhold }}</p>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="glass p-3 mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <select id="statusFilter" class="form-select control">
                    <option value="">Semua Status</option>
                    <option value="submitted">Submitted</option>
                    <option value="review">Review</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="onhold">On Hold</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <select id="typeFilter" class="form-select control">
                    <option value="">Semua Jenis</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Sponsorship">Sponsorship</option>
                    <option value="Kegiatan">Kegiatan</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Event">Event</option>
                    <option value="Penelitian">Penelitian</option>
                    <option value="Magang">Magang</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0" style="border-radius:14px 0 0 14px;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input id="searchInput" type="text" class="form-control control border-0"
                           placeholder="Cari nama instansi / email / penanggung jawab..."
                           style="border-radius:0 14px 14px 0;">
                </div>
                <div class="muted mt-1">Filter ini hanya berlaku di halaman ini.</div>
            </div>

            <div class="col-12 col-md-2 d-flex justify-content-md-end gap-2">
                <button id="resetFilter" type="button" class="btn btn-soft btn-sm">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </button>
                <span class="btn btn-primary btn-sm disabled">
                    Total: {{ $proposals->total() }}
                </span>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="glass">
        <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="fw-bold text-dark">
                <i class="bi bi-table me-1"></i> Data Proposal
            </div>
            <div class="muted">
                Menampilkan {{ $proposals->count() }} dari {{ $proposals->total() }} data
            </div>
        </div>

        <div class="divider"></div>

        <div class="table-responsive table-wrap">
            <table class="table align-middle mb-0" id="appsTable">
                <thead>
                    <tr>
                        <th style="width:70px;">No</th>
                        <th>Nama Instansi</th>
                        <th>Penanggung Jawab</th>
                        <th>Jenis Kerjasama</th>
                        <th>Proposal</th>
                        <th>Status</th>
                        <th class="text-end" style="width:220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($proposals as $index => $p)
                        @php
                            $statusKey = strtolower(trim($p->status ?? 'submitted'));
                            $badgeClass = $statusKey === 'approved' ? 'st-approved'
                                : ($statusKey === 'rejected' ? 'st-rejected'
                                : ($statusKey === 'pending' ? 'st-pending'
                                : ($statusKey === 'review' ? 'st-review'
                                : ($statusKey === 'onhold' ? 'st-onhold' : 'st-submitted'))));

                            $keySearch = \Illuminate\Support\Str::lower(
                                ($p->organization_name ?? '') . ' ' .
                                ($p->contact_email ?? '') . ' ' .
                                ($p->contact_person ?? '') . ' ' .
                                ($p->contact_phone ?? '')
                            );
                        @endphp

                        <tr class="row-item"
                            data-key="{{ $keySearch }}"
                            data-status="{{ $statusKey }}"
                            data-type="{{ $p->cooperation_type ?? '' }}">

                            <td class="muted">{{ $proposals->firstItem() + $index }}</td>

                            <td style="min-width:280px;">
                                <div class="fw-semibold">{{ $p->organization_name }}</div>
                                <div class="muted small">{{ $p->contact_email ?? '-' }}</div>
                                <div class="xsmall">{{ optional($p->created_at)->translatedFormat('d F Y') }}</div>
                            </td>

                            <td style="min-width:240px;">
                                <div class="fw-semibold">{{ $p->contact_person ?? '-' }}</div>
                                <div class="muted small">{{ $p->position ?? '-' }}</div>
                                <div class="xsmall">{{ $p->contact_phone ?? '-' }}</div>
                            </td>

                            <td>
                                <span class="badge-soft badge-type">
                                    <i class="bi bi-diagram-3"></i> {{ $p->cooperation_type ?? '—' }}
                                </span>
                            </td>

                            <td>
                                @if ($p->document_path)
                                    <a href="{{ route('admin.partnerships.download', $p->id) }}"
                                       class="btn btn-sm btn-download d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="muted small">Tidak ada file</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge-soft {{ $badgeClass }}">
                                    <i class="bi bi-circle-fill" style="font-size:.55rem;"></i> {{ ucfirst($statusKey) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.partnerships.show', $p->id) }}"
                                   class="btn btn-primary btn-sm btn-icon me-1"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.partnerships.edit', $p->id) }}"
                                   class="btn btn-warning btn-sm btn-icon me-1"
                                   title="Update">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.partnerships.destroy', $p->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-outline-danger btn-sm btn-icon"
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus proposal ini? File proposal (jika ada) juga akan terhapus.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="fw-semibold mb-1">Belum ada pengajuan</div>
                                <div class="muted">Data akan muncul setelah user mengirim proposal kerja sama.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($proposals->hasPages())
            <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $proposals->firstItem() }}–{{ $proposals->lastItem() }} dari {{ $proposals->total() }}
                </div>
                <div>
                    {{ $proposals->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    (function () {
        const rows = [...document.querySelectorAll('#tableBody .row-item')];
        const statusSel = document.getElementById('statusFilter');
        const typeSel = document.getElementById('typeFilter');
        const searchInp = document.getElementById('searchInput');
        const resetBtn = document.getElementById('resetFilter');

        function applyFilter() {
            const q  = (searchInp.value || '').trim().toLowerCase();
            const st = (statusSel.value || '').toLowerCase();
            const tp = (typeSel.value || '').toLowerCase();

            rows.forEach(r => {
                const key = (r.dataset.key || '');
                const okStatus = !st || (String(r.dataset.status || '').toLowerCase() === st);
                const okType = !tp || (String(r.dataset.type || '').toLowerCase() === tp);
                const okSearch = !q || key.includes(q);
                r.style.display = (okStatus && okType && okSearch) ? '' : 'none';
            });
        }

        statusSel?.addEventListener('change', applyFilter);
        typeSel?.addEventListener('change', applyFilter);
        searchInp?.addEventListener('input', applyFilter);

        resetBtn?.addEventListener('click', () => {
            if (statusSel) statusSel.value = '';
            if (typeSel) typeSel.value = '';
            if (searchInp) searchInp.value = '';
            applyFilter();
        });
    })();
</script>
@endsection
