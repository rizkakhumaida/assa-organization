@extends('layouts.app')

@section('content')

<style>
/* ---------------------------- */
/* 🌟 ULTRA MODERN PREMIUM UI   */
/* ---------------------------- */

/* Global smooth animation */
* {
    transition: .25s ease;
}

/* Gradient Header */
.gradient-header {
    background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 50%, #60A5FA 100%);
    padding: 2.4rem;
    border-radius: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(30,58,138,0.25);
}
.gradient-header::after {
    content:"";
    position:absolute;
    top:-60px; right:-60px;
    width:200px; height:200px;
    background:rgba(255,255,255,0.18);
    border-radius:50%;
    filter: blur(8px);
}

/* Glass Card */
.glass-card {
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(14px);
    border-radius:1.25rem;
    border: 1px solid rgba(255,255,255,0.35);
    padding:1.4rem;
    box-shadow: 0 10px 35px rgba(0,0,0,0.09);
}
.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 45px rgba(0,0,0,0.15);
}

/* Search Input Rounded */
.input-rounded {
    border-radius: 0.85rem !important;
}

/* Category Badges — Neon Style */
.badge-category {
    padding: .45rem 1rem;
    border-radius: 1rem;
    font-size: .75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    letter-spacing: .3px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}
.badge-category i { font-size:.85rem; }

/* Neon category colors */
.badge-category.seminar { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
.badge-category.outbound { background:#dcfce7; color:#166534; border:1px solid #86efac; }
.badge-category.kunjungan { background:#fff3c4; color:#92400e; border:1px solid #fde68a; }
.badge-category.event { background:#ffe4e6; color:#be123c; border:1px solid #f9a8d4; }
.badge-category.lainnya { background:#e5e7eb; color:#374151; border:1px solid #d1d5db; }

/* Card: Elevated Neo-Glass Style */
.activity-card {
    position: relative;
    background: rgba(255,255,255,0.9);
    border-radius:1.5rem;
    padding:1.7rem;
    border:1px solid rgba(229,231,235,0.8);
    box-shadow:0 12px 28px rgba(30,58,138,0.07);
}
.activity-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 45px rgba(30,58,138,0.18);
}

/* Top floating strip */
.activity-strip {
    height:6px;
    width:100%;
    border-radius:12px;
    margin-bottom:1rem;
    background:linear-gradient(135deg,#2563EB,#60A5FA);
    opacity:.8;
}

/* Status badge */
.badge-status {
    padding:.45rem .75rem;
    border-radius:1rem;
    font-size:.75rem;
    font-weight:600;
}
.badge-upcoming { background:#dbeafe; color:#1d4ed8; }
.badge-ongoing { background:#dcfce7; color:#15803d; }
.badge-finished { background:#f3f4f6; color:#6b7280; }

/* Buttons */
.btn-modern {
    border-radius:.9rem;
    padding:.65rem 1.1rem;
    font-weight:600;
}
.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow:0 6px 16px rgba(0,0,0,0.15);
}

.btn-view { border:1px solid #1E3A8A; color:#1E3A8A; background:white; }
.btn-edit { background:linear-gradient(135deg,#1E3A8A,#3B82F6); color:white; }
.btn-delete { background:#ef4444; color:white; }

/* Grid */
.activity-grid {
    display:grid;
    grid-template-columns: repeat(auto-fill,minmax(350px,1fr));
    gap:1.6rem;
}
</style>

<div class="page-container">

    {{-- HEADER --}}
    <div class="gradient-header mb-4">
        <h1 class="fw-bold fs-2">📅 Manajemen Kegiatan</h1>
        <p class="mb-0" style="opacity:.92;">Tampilan admin modern untuk mengelola kegiatan ASSA</p>
    </div>

    {{-- SEARCH & ACTION --}}
    <div class="glass-card mb-4 d-flex flex-wrap gap-3 justify-content-between align-items-center">

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.activities.index') }}" class="flex-grow-1">
            <div class="input-group">
                <input name="q" value="{{ $q ?? '' }}"
                       type="text"
                       placeholder="🔍 Cari judul, kategori, lokasi..."
                       class="form-control input-rounded">

                <button class="btn btn-modern btn-edit" type="submit">
                    <i class="fas fa-search me-1"></i> Cari
                </button>

                @if(!empty($q))
                <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-modern">
                    Reset
                </a>
                @endif
            </div>
        </form>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2">
            <a href="{{ route('admin.activities.create') }}" class="btn btn-success-assa btn-modern">
                <i class="fas fa-plus-circle me-1"></i> Tambah
            </a>

            <a href="{{ route('admin.activities.index') }}" class="btn btn-warning-assa btn-modern">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </a>
        </div>
    </div>

    {{-- LIST --}}
    @if($activities->count() === 0)

        <div class="glass-card text-center p-5">
            <i class="fas fa-calendar-times mb-3" style="font-size:3.2rem; color:#6b7280;"></i>
            <h4 class="fw-bold text-muted">Tidak Ada Kegiatan</h4>
            <p class="text-muted mb-3">Tambahkan kegiatan baru untuk mulai mengelola agenda</p>
            <a href="{{ route('admin.activities.create') }}" class="btn btn-success-assa btn-modern">
                Tambah Kegiatan
            </a>
        </div>

    @else

    <div class="activity-grid">

        @foreach($activities as $act)
        @php
            $cat = strtolower($act->category ?? 'Lainnya');
            $badgeClass = match($cat) {
                'seminar' => 'seminar',
                'outbound' => 'outbound',
                'kunjungan' => 'kunjungan',
                'event olahraga', 'event', 'olahraga' => 'event',
                default => 'lainnya',
            };

            $start = \Carbon\Carbon::parse($act->start_at);
            $end = \Carbon\Carbon::parse($act->end_at);
            $now = now();
            $status = $now->between($start,$end) ? 'ongoing' : ($now->gt($end) ? 'finished' : 'upcoming');
        @endphp

        <div class="activity-card">

            <div class="activity-strip"></div>

            <span class="badge-category {{ $badgeClass }}">
                <i class="fas fa-tag"></i>
                {{ ucfirst($act->category ?? 'Lainnya') }}
            </span>

            <h3 class="fw-bold assa-blue mt-2">{{ $act->title }}</h3>

            <div class="my-2">
                @if($status==='upcoming')
                    <span class="badge-status badge-upcoming">🚀 Akan Datang</span>
                @elseif($status==='ongoing')
                    <span class="badge-status badge-ongoing">⏳ Berlangsung</span>
                @else
                    <span class="badge-status badge-finished">✔ Selesai</span>
                @endif
            </div>

            <p class="text-muted mb-1">
                <i class="fas fa-map-marker-alt me-2"></i> {{ $act->location ?? 'TBA' }}
            </p>

            <p class="text-muted mb-3">
                <i class="fas fa-calendar me-2"></i>
                {{ $start->format('d M Y H:i') }} → {{ $end->format('d M Y H:i') }}
            </p>

            <p class="text-muted">{{ \Str::limit($act->description,120) }}</p>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.activities.show',$act->id) }}" class="btn-modern btn-view w-100">
                    <i class="fas fa-eye"></i> Detail
                </a>

                <a href="{{ route('admin.activities.edit',$act->id) }}" class="btn-modern btn-edit w-100">
                    <i class="fas fa-edit"></i> Edit
                </a>

                <form method="POST" action="{{ route('admin.activities.destroy',$act->id) }}"
                      onsubmit="return confirm('Yakin ingin menghapus kegiatan?')" class="w-100">
                    @csrf
                    @method('DELETE')
                    <button class="btn-modern btn-delete w-100">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>

        </div>

        @endforeach

    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $activities->links() }}
    </div>

    @endif

</div>

@endsection
