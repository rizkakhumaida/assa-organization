@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $now = now();

    // Statistik berdasarkan data yang tampil di halaman ini (collection current page)
    $pageItems = $activities instanceof \Illuminate\Pagination\AbstractPaginator ? $activities->getCollection() : collect($activities);

    $countUpcoming = $pageItems->filter(function($a) use ($now){
        if(!$a->start_at || !$a->end_at) return false;
        return $now->lt(Carbon::parse($a->start_at));
    })->count();

    $countOngoing = $pageItems->filter(function($a) use ($now){
        if(!$a->start_at || !$a->end_at) return false;
        $start = Carbon::parse($a->start_at);
        $end   = Carbon::parse($a->end_at);
        return $now->between($start, $end);
    })->count();

    $countFinished = $pageItems->filter(function($a) use ($now){
        if(!$a->end_at) return false;
        return $now->gt(Carbon::parse($a->end_at));
    })->count();

    $normCat = fn($s) => strtolower(trim($s ?? 'lainnya'));
@endphp

<style>
    body{
        background:
            radial-gradient(1100px 520px at 15% 0%, #dbeafe 0%, transparent 60%),
            radial-gradient(900px 520px at 85% 10%, #e0f2fe 0%, transparent 55%),
            linear-gradient(135deg, #f1f5f9, #eef2ff);
        font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }
    *{ transition: .2s ease; }

    .hero{
        background: linear-gradient(90deg, #1e40af, #2563eb);
        border-radius: 18px;
        padding: 22px 24px;
        color:#fff;
        box-shadow: 0 16px 38px rgba(37,99,235,.32);
        position:relative;
        overflow:hidden;
    }
    .hero:after{
        content:"";
        position:absolute;
        top:-70px; right:-70px;
        width:240px; height:240px;
        background:rgba(255,255,255,.16);
        border-radius:50%;
        filter: blur(10px);
    }
    .hero h3{ margin:0; font-weight:900; letter-spacing:.2px; }
    .hero small{ opacity:.9; font-size:.9rem; }

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
        background: rgba(255,255,255,.90);
        box-shadow: 0 10px 25px rgba(2,6,23,.05);
    }
    .stat-title{ font-size:.82rem; color:#64748b; margin:0; }
    .stat-value{ font-size:1.55rem; font-weight:900; margin:0; color:#0f172a; }
    .stat-chip{
        font-size:.74rem;
        padding:.32rem .62rem;
        border-radius: 999px;
        background: #eef2ff;
        color:#3730a3;
        font-weight: 800;
        border:1px solid rgba(99,102,241,.18);
        white-space:nowrap;
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

    .btn-icon{
        width: 40px; height: 40px;
        display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 12px;
    }

    .grid{
        display:grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.2rem;
    }

    .activity-card{
        background: rgba(255,255,255,.92);
        border: 1px solid rgba(226,232,240,.85);
        border-radius: 18px;
        padding: 16px 16px 14px;
        box-shadow: 0 12px 28px rgba(30,58,138,0.08);
        position:relative;
        overflow:hidden;
    }
    .activity-card:hover{
        transform: translateY(-6px);
        box-shadow: 0 18px 45px rgba(30,58,138,0.18);
    }

    .strip{
        height: 6px;
        border-radius: 999px;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
        opacity:.85;
        margin-bottom: 12px;
    }

    .muted{ color:#64748b; font-size:.92rem; }
    .xsmall{ font-size:.76rem; color:#64748b; }

    .badge-soft{
        padding:.45rem .7rem;
        border-radius: 999px;
        font-weight: 900;
        font-size:.78rem;
        letter-spacing:.2px;
        border: 1px solid transparent;
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        white-space:nowrap;
    }

    /* Status badges */
    .st-upcoming{ background: rgba(59,130,246,.12); color:#1e40af; border-color: rgba(59,130,246,.22); }
    .st-ongoing{  background: rgba(34,197,94,.12); color:#166534; border-color: rgba(34,197,94,.22); }
    .st-finished{ background: rgba(100,116,139,.12); color:#0f172a; border-color: rgba(100,116,139,.20); }

    /* Category badges (auto mapping) */
    .cat-seminar{ background:#dbeafe; color:#1e40af; border-color:#93c5fd; }
    .cat-outbound{ background:#dcfce7; color:#166534; border-color:#86efac; }
    .cat-kunjungan{ background:#fff3c4; color:#92400e; border-color:#fde68a; }
    .cat-event{ background:#ffe4e6; color:#be123c; border-color:#f9a8d4; }
    .cat-lainnya{ background:#e5e7eb; color:#374151; border-color:#d1d5db; }

    .pill-action{
        border-radius: 999px;
        padding:.55rem .9rem;
        font-weight: 700;
    }

    .divider{
        height:1px;
        background: linear-gradient(90deg, transparent, rgba(15,23,42,.12), transparent);
    }
</style>

<div class="container py-4">

    {{-- HERO HEADER --}}
    <div class="hero mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                <i class="bi bi-calendar2-week-fill fs-5"></i>
            </div>
            <div>
                <h3>Manajemen Kegiatan</h3>
                <small>Kelola agenda ASSA: buat, pantau jadwal, edit, dan hapus kegiatan</small>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.activities.create') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Tambah
            </a>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="bi bi-arrow-repeat me-1"></i> Refresh
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

    {{-- STATS (data pada halaman ini) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Akan Datang</p><span class="stat-chip">Upcoming</span>
                </div>
                <p class="stat-value">{{ $countUpcoming }}</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Berlangsung</p><span class="stat-chip">Ongoing</span>
                </div>
                <p class="stat-value">{{ $countOngoing }}</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Selesai</p><span class="stat-chip">Finished</span>
                </div>
                <p class="stat-value">{{ $countFinished }}</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="stat-title">Total (Halaman Ini)</p><span class="stat-chip">Page</span>
                </div>
                <p class="stat-value">{{ $pageItems->count() }}</p>
            </div>
        </div>
    </div>

    {{-- FILTER / SEARCH --}}
    <div class="glass p-3 mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-7">
                <form method="GET" action="{{ route('admin.activities.index') }}">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0" style="border-radius:14px 0 0 14px;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input name="q"
                               value="{{ $q ?? request('q') }}"
                               type="text"
                               class="form-control control border-0"
                               placeholder="Cari judul, kategori, lokasi..."
                               style="border-radius:0 14px 14px 0;">
                        <button class="btn btn-primary pill-action ms-2" type="submit">
                            <i class="bi bi-funnel me-1"></i> Cari
                        </button>
                        @if(!empty($q ?? request('q')))
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary pill-action ms-2">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </a>
                        @endif
                    </div>
                    <div class="muted mt-1">Pencarian menggunakan query server (lebih akurat untuk data banyak).</div>
                </form>
            </div>

            <div class="col-12 col-md-5 d-flex justify-content-md-end gap-2 flex-wrap">
                <span class="btn btn-primary btn-sm disabled">
                    Total: {{ $activities instanceof \Illuminate\Pagination\AbstractPaginator ? $activities->total() : $pageItems->count() }}
                </span>
            </div>
        </div>
    </div>

    {{-- LIST --}}
    @if($pageItems->count() === 0)
        <div class="glass text-center p-5">
            <div class="mb-3">
                <i class="bi bi-calendar-x fs-1 text-secondary"></i>
            </div>
            <h5 class="fw-bold text-muted mb-1">Tidak Ada Kegiatan</h5>
            <div class="muted mb-3">Tambahkan kegiatan baru untuk mulai mengelola agenda</div>
            <a href="{{ route('admin.activities.create') }}" class="btn btn-success pill-action">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kegiatan
            </a>
        </div>
    @else

        <div class="grid">
            @foreach($activities as $act)
                @php
                    $cat = $normCat($act->category);
                    $catClass = match(true) {
                        str_contains($cat, 'seminar') => 'cat-seminar',
                        str_contains($cat, 'outbound') => 'cat-outbound',
                        str_contains($cat, 'kunjungan') => 'cat-kunjungan',
                        str_contains($cat, 'event') || str_contains($cat, 'olahraga') => 'cat-event',
                        default => 'cat-lainnya',
                    };

                    $start = $act->start_at ? Carbon::parse($act->start_at) : null;
                    $end   = $act->end_at ? Carbon::parse($act->end_at) : null;

                    $status = 'upcoming';
                    if($start && $end){
                        $status = $now->between($start, $end) ? 'ongoing' : ($now->gt($end) ? 'finished' : 'upcoming');
                    } elseif($end && $now->gt($end)){
                        $status = 'finished';
                    }

                    $stClass = $status === 'ongoing' ? 'st-ongoing' : ($status === 'finished' ? 'st-finished' : 'st-upcoming');
                    $stLabel = $status === 'ongoing' ? 'Berlangsung' : ($status === 'finished' ? 'Selesai' : 'Akan Datang');
                @endphp

                <div class="activity-card">
                    <div class="strip"></div>

                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <span class="badge-soft {{ $catClass }}">
                            <i class="bi bi-tag-fill"></i> {{ ucfirst($act->category ?? 'Lainnya') }}
                        </span>

                        <span class="badge-soft {{ $stClass }}">
                            <i class="bi bi-circle-fill" style="font-size:.55rem;"></i> {{ $stLabel }}
                        </span>
                    </div>

                    <h5 class="fw-bold mt-3 mb-2" style="color:#0f172a;">
                        {{ $act->title }}
                    </h5>

                    <div class="muted mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $act->location ?? 'TBA' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar3"></i>
                            <span>
                                @if($start && $end)
                                    {{ $start->format('d M Y H:i') }} → {{ $end->format('d M Y H:i') }}
                                @else
                                    Jadwal belum lengkap
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="xsmall mb-3">
                        {{ \Illuminate\Support\Str::limit($act->description, 140) }}
                    </div>

                    <div class="divider mb-3"></div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.activities.show', $act->id) }}"
                           class="btn btn-primary btn-sm btn-icon"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('admin.activities.edit', $act->id) }}"
                           class="btn btn-warning btn-sm btn-icon"
                           title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.activities.destroy', $act->id) }}"
                              class="ms-auto"
                              onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-outline-danger btn-sm btn-icon"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
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
