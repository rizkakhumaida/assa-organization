@extends('layouts.app')

@section('title', 'Kegiatan - Anggota')

@section('content')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    $selectedCategory = request('category');

    // FILTER BERDASARKAN KATEGORI (langsung di Blade)
    if ($selectedCategory && $selectedCategory !== 'Semua') {
        $activities = $activities->filter(function($a) use ($selectedCategory) {
            $judul = strtolower($a->title);

            return match($selectedCategory) {
                'Seminar' => str_contains($judul, 'seminar') || str_contains($judul, 'produktif'),
                'Outbound' => str_contains($judul, 'outbound'),
                'Kunjungan' => str_contains($judul, 'kunjungan') || str_contains($judul, 'visit') || str_contains($judul, 'studi'),
                'Event Olahraga' => str_contains($judul, 'futsal') || str_contains($judul, 'olahraga'),
                default => true
            };
        });
    }
@endphp

<style>
  .btn-filter {
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      font-weight: 600;
      font-size: 0.875rem;
      transition: all 0.3s ease;
      cursor: pointer;
      border: 1px solid #e5e7eb;
      text-decoration: none;
  }

  .btn-filter.active {
      background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
      color: white !important;
      border-color: #1E3A8A;
  }

  .btn-filter:not(.active) {
      background: white;
      color: #6b7280;
  }

  .btn-filter:not(.active):hover {
      background: #f8fafc;
      color: #1E3A8A;
  }

  .activity-card-enhanced {
      background: white;
      border-radius: 1rem;
      overflow: hidden;
      border: 1px solid #e5e7eb;
      transition: all .3s ease;
  }

  .activity-card-enhanced:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);
  }
</style>

<div class="page-container">

  {{-- Header --}}
  <section class="header-section mb-4">
    <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex">
          <div class="d-flex align-items-center justify-content-center rounded-xl me-3"
               style="width:48px;height:48px;background:rgba(255,255,255,0.2);">
            <i class="fas fa-calendar-alt fa-lg"></i>
          </div>

          <div class="text-start">
            <h1 class="fw-bold mb-1 fs-2">Kegiatan ASSA Organization</h1>
            <p class="mb-0 fs-5" style="color:#bfdbfe;">Temukan berbagai kegiatan menarik</p>
          </div>
        </div>
    </div>
  </section>

  {{-- Search + Filter --}}
  <section class="search-filter-section mb-4">
    <div class="bg-white rounded-xl p-4 shadow-sm border">

      <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">

        {{-- Search --}}
        <form action="{{ route('anggota.activities.index') }}" method="GET" class="flex-grow-1" style="max-width:400px;">
          <label class="form-label fw-semibold assa-blue mb-2">
            <i class="fas fa-search me-1"></i> Pencarian Kegiatan
          </label>

          <div class="position-relative">
            <i class="fas fa-search position-absolute"
               style="left:.75rem; top:50%; transform:translateY(-50%); color:#9ca3af;"></i>

            <input name="search"
                   value="{{ request('search') }}"
                   type="text"
                   placeholder="Cari kegiatan..."
                   class="search-input"
                   style="padding-left:2.5rem;">
          </div>
        </form>

        {{-- Filter Buttons --}}
        <div>
          <label class="form-label fw-semibold assa-blue mb-2 d-block">
            <i class="fas fa-filter me-1"></i> Filter Kategori
          </label>

          <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('anggota.activities.index') }}"
              class="btn-filter {{ $selectedCategory == '' ? 'active' : '' }}">
              Semua
            </a>

            <a href="{{ route('anggota.activities.index', ['category' => 'Seminar']) }}"
              class="btn-filter {{ $selectedCategory == 'Seminar' ? 'active' : '' }}">
              Seminar
            </a>

            <a href="{{ route('anggota.activities.index', ['category' => 'Outbound']) }}"
              class="btn-filter {{ $selectedCategory == 'Outbound' ? 'active' : '' }}">
              Outbound
            </a>

            <a href="{{ route('anggota.activities.index', ['category' => 'Kunjungan']) }}"
              class="btn-filter {{ $selectedCategory == 'Kunjungan' ? 'active' : '' }}">
              Kunjungan
            </a>

            <a href="{{ route('anggota.activities.index', ['category' => 'Event Olahraga']) }}"
              class="btn-filter {{ $selectedCategory == 'Event Olahraga' ? 'active' : '' }}">
              Event Olahraga
            </a>

          </div>
        </div>

      </div>

    </div>
  </section>

  {{-- Activities List --}}
  <section class="activities-section">
    <div class="activities-grid"
         style="display:grid; grid-template-columns:repeat(auto-fill, minmax(350px, 1fr)); gap:1.5rem;">

      @forelse($activities as $activity)

        @php
            $lower = strtolower($activity->title);

            if (str_contains($lower, 'seminar') || str_contains($lower, 'produktif')) {
                $kategori = 'Seminar';
            } elseif (str_contains($lower, 'outbound')) {
                $kategori = 'Outbound';
            } elseif (str_contains($lower, 'kunjungan') || str_contains($lower, 'visit') || str_contains($lower, 'studi')) {
                $kategori = 'Kunjungan';
            } elseif (str_contains($lower, 'futsal') || str_contains($lower, 'olahraga')) {
                $kategori = 'Event Olahraga';
            } else {
                $kategori = 'Lainnya';
            }

            $judul = $activity->title;
            $deskripsi = Str::limit($activity->description, 160);

            $tanggal = $activity->start_at
                ? Carbon::parse($activity->start_at)->format('d F Y')
                : 'Tanggal akan diumumkan';

            $lokasi = $activity->location ?? 'TBA';
        @endphp

        <article class="activity-card-enhanced">

          <div class="p-4 text-white"
               style="background:linear-gradient(135deg,#1e3a8a,#3b82f6); position:relative; height:140px;">
            <span class="category-badge-enhanced"
                  style="position:absolute; top:1rem; left:1rem; padding:.4rem .8rem;
                  border-radius:.75rem; background:rgba(0,0,0,.3); backdrop-filter:blur(4px);">
              {{ $kategori }}
            </span>
            <i class="fas fa-calendar-check"
               style="font-size:3rem; opacity:.4; position:absolute; bottom:1rem; right:1rem;"></i>
          </div>

          <div class="p-4">
            <h3 class="fw-bold assa-blue">{{ $judul }}</h3>

            <div class="mt-3 text-muted" style="font-size:.875rem;">
              <div><i class="fas fa-calendar me-2"></i> {{ $tanggal }}</div>
              <div><i class="fas fa-map-marker-alt me-2"></i> {{ $lokasi }}</div>
            </div>

            <p class="mt-3 text-muted" style="font-size:.875rem;">
              {{ $deskripsi }}
            </p>
          </div>

          <div class="p-3 bg-light border-top">
            <a href="{{ route('anggota.activities.show', $activity->id) }}"
               class="btn-assa w-100">
              <i class="fas fa-eye me-2"></i> Lihat Detail
            </a>
          </div>

        </article>

      @empty

        <div class="empty-state text-center p-5 border rounded-3"
             style="grid-column:1 / -1;">
          <h4 class="text-muted fw-bold">Tidak Ada Kegiatan</h4>
          <p class="text-muted">Tidak ditemukan kegiatan dengan filter ini.</p>
        </div>

      @endforelse

    </div>

  </section>

</div>

@endsection
