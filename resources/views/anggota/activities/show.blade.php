@extends('layouts.app')

@section('title', $activity->title ?? 'Detail Kegiatan')

@section('content')
    @php
        use Illuminate\Support\Str;
        use Carbon\Carbon;

        // Kolom sesuai database:
        // title, description, start_at, end_at, location, category

        $judul = $activity->title ?? 'Kegiatan';
        $kategori = $activity->category ?? 'Lainnya';
        $deskripsi = $activity->description ?? 'Tidak ada deskripsi.';

        // Format tanggal
        $tanggal = $activity->start_at
            ? Carbon::parse($activity->start_at)->translatedFormat('d F Y')
            : 'Tanggal akan diumumkan';

        // Lokasi
        $lokasi = $activity->location ?? 'TBA';

        // Style badge kategori
        $kategoriSlug = Str::slug($kategori);
        $badgeStyle = match ($kategoriSlug) {
            'seminar' => 'background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;',
            'outbound' => 'background: linear-gradient(135deg, #10b981, #047857); color: white;',
            'kunjungan' => 'background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;',
            'event-olahraga' => 'background: linear-gradient(135deg, #06b6d4, #0891b2); color: white;',
            default => 'background: linear-gradient(135deg, #6b7280, #4b5563); color: white;',
        };
    @endphp

    <style>
        .assa-blue {
            color: #1E3A8A;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        }

        .badge-category {
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .info-card {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.07);
        }

        .info-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        .card-enhanced {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            transition: 0.3s ease;
        }

        .card-enhanced:hover {
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.08);
        }
    </style>

    <div class="page-container">

        {{-- Breadcrumb --}}
        <nav class="mb-4">
            <ol class="d-flex align-items-center text-sm text-muted">
                <li><a href="{{ route('anggota.activities.index') }}" class="text-decoration-none assa-blue">Kegiatan</a></li>
                <li class="mx-2">/</li>
                <li class="text-muted">{{ Str::limit($judul, 40) }}</li>
            </ol>
        </nav>

        {{-- Header Section --}}
        <section class="header-section mb-4">
            <div class="gradient-bg text-white rounded-2xl p-4">
                <div class="row align-items-center">
                    <div class="col-lg-10">
                        <span class="badge-category mb-2" style="{{ $badgeStyle }}">
                            {{ ucfirst($kategori) }}
                        </span>

                        <h1 class="fw-bold mb-2 fs-2">{{ $judul }}</h1>
                        <p class="mb-0 fs-5" style="color: #bfdbfe;">Detail kegiatan ASSA Organization</p>
                    </div>

                    <div class="col-lg-2 text-center d-none d-lg-block">
                        <div class="rounded-xl d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; background: rgba(255,255,255,0.25); margin: auto;">
                            <i class="fas fa-calendar-check" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Info Section --}}
        <section class="info-section mb-4">
            <div class="row g-3">

                {{-- Tanggal --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-icon" style="background: #3b82f6; color:white;">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Tanggal Kegiatan
                            </p>
                            <p class="fw-bold assa-blue mb-0">{{ $tanggal }}</p>
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-icon" style="background: #10b981; color:white;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Lokasi</p>
                            <p class="fw-bold assa-blue mb-0">{{ $lokasi }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-icon" style="background: #8b5cf6; color:white;">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Kategori</p>
                            <p class="fw-bold assa-blue mb-0">{{ ucfirst($kategori) }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Deskripsi --}}
        <section class="description-section mb-4">
            <div class="card-enhanced p-4">
                <h4 class="fw-bold assa-blue mb-3">Deskripsi Kegiatan</h4>
                <p class="text-muted" style="line-height:1.7;">{{ $deskripsi }}</p>
            </div>
        </section>

        {{-- Actions --}}
        <section>
            <div class="card-enhanced p-4">
                <div class="d-flex justify-content-between flex-wrap gap-2">

                    <a href="{{ route('anggota.activities.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>

                    <a href="{{ route('anggota.activities.create', $activity->id) }}" class="btn btn-success text-white">
                        <i class="fas fa-user-plus me-2"></i> Daftar Kegiatan
                    </a>

                </div>
            </div>
        </section>

    </div>

@endsection
