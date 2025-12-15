@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <span class="text-danger"><i class="bi bi-clipboard2-check"></i></span>
                Daftar Pengajuan Kerjasama
            </h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.partnerships.export.pdf') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filetype-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('admin.partnerships.export.excel') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-filetype-xlsx me-1"></i> Export Excel
                </a>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-3">
                        <select id="statusFilter" class="form-select form-select-sm rounded-pill">
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
                        <select id="typeFilter" class="form-select form-select-sm rounded-pill">
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

                    <div class="col-12 col-md">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input id="searchInput" type="text" class="form-control border-start-0"
                                placeholder="Cari nama instansi atau penanggung jawab…">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="small text-muted sticky-top bg-white">
                        <tr>
                            <th class="py-3 ps-4">NAMA INSTANSI</th>
                            <th>PENANGGUNG JAWAB</th>
                            <th>JENIS KERJASAMA</th>
                            <th>PROPOSAL</th>
                            <th>STATUS</th>
                            <th class="text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($proposals as $p)
                            <tr class="row-item"
                                data-key="{{ \Illuminate\Support\Str::lower(($p->organization_name ?? '') . ' ' . ($p->contact_email ?? '') . ' ' . ($p->contact_phone ?? '')) }}"
                                data-status="{{ strtolower($p->status ?? '') }}"
                                data-type="{{ $p->cooperation_type ?? '' }}">

                                {{-- Nama Instansi --}}
                                <td class="ps-4" style="min-width:260px;">
                                    <div class="fw-semibold">{{ $p->organization_name }}</div>
                                    <div class="text-muted small">{{ $p->contact_email ?? '-' }}</div>
                                    <div class="text-muted xsmall">{{ optional($p->created_at)->translatedFormat('d F Y') }}
                                    </div>
                                </td>

                                {{-- Penanggung Jawab --}}
                                <td style="min-width:220px;">
                                    <div class="fw-semibold">{{ $p->contact_person ?? '-' }}</div>
                                    <div class="text-muted small">{{ $p->position ?? '-' }}</div>
                                    <div class="text-muted xsmall">{{ $p->contact_phone ?? '-' }}</div>
                                </td>

                                {{-- Jenis Kerjasama --}}
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 fw-semibold badge-type">
                                        {{ $p->cooperation_type ?? '—' }}
                                    </span>
                                </td>

                                {{-- Proposal --}}
                                <td>
                                    @if ($p->document_path)
                                        <a href="{{ route('admin.partnerships.download', $p->id) }}"
                                            class="btn btn-sm btn-download d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada file</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @php($s = strtolower($p->status ?? 'submitted'))
                                    <span
                                        class="badge rounded-pill px-3 py-2 fw-semibold
                                    {{ $s === 'approved' ? 'bg-status-approved' : '' }}
                                    {{ $s === 'rejected' ? 'bg-status-rejected' : '' }}
                                    {{ $s === 'pending' ? 'bg-status-pending' : '' }}
                                    {{ $s === 'onhold' ? 'bg-status-onhold' : '' }}
                                    {{ $s === 'review' ? 'bg-status-review' : '' }}
                                    {{ $s === 'submitted' ? 'bg-status-submitted' : '' }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.partnerships.show', $p->id) }}"
                                        class="link-primary me-3">Detail</a>
                                    <a href="{{ route('admin.partnerships.edit', $p->id) }}"
                                        class="link-secondary">Update</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">Belum ada pengajuan.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($proposals->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $proposals->firstItem() }}–{{ $proposals->lastItem() }} dari
                            {{ $proposals->total() }}
                        </div>
                        {{ $proposals->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Styles --}}
    <style>
        .table> :not(caption)>*>* {
            padding: 1rem .75rem;
        }

        .badge-type {
            background: #e8f1ff;
            color: #1b57f2;
        }

        .btn-download {
            background: #159a5a;
            color: #fff;
            border: none;
        }

        .btn-download:hover {
            filter: brightness(.95);
            color: #fff;
        }

        .bg-status-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .bg-status-approved {
            background: #D1FAE5;
            color: #065F46;
        }

        .bg-status-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }

        .bg-status-onhold {
            background: #EDE9FE;
            color: #5B21B6;
        }

        .bg-status-review {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .bg-status-submitted {
            background: #F3F4F6;
            color: #374151;
        }

        .xsmall {
            font-size: .74rem;
        }

        .card {
            border-radius: .9rem;
        }
    </style>

    {{-- JS filter client-side --}}
    <script>
        const rows = [...document.querySelectorAll('#tableBody .row-item')];
        const statusSel = document.getElementById('statusFilter');
        const typeSel = document.getElementById('typeFilter');
        const searchInp = document.getElementById('searchInput');

        function applyFilter() {
            const q = (searchInp.value || '').trim().toLowerCase();
            const st = (statusSel.value || '').toLowerCase();
            const tp = (typeSel.value || '').toLowerCase();

            rows.forEach(r => {
                const key = r.dataset.key || '';
                const okStatus = !st || (r.dataset.status === st);
                const okType = !tp || (r.dataset.type?.toLowerCase() === tp);
                const okSearch = !q || key.includes(q);
                r.style.display = (okStatus && okType && okSearch) ? '' : 'none';
            });
        }

        statusSel.addEventListener('change', applyFilter);
        typeSel.addEventListener('change', applyFilter);
        searchInp.addEventListener('input', applyFilter);
    </script>
@endsection
