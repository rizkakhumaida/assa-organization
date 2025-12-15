@extends('layouts.app')

@section('content')
<style>
  /* Custom CSS untuk menyelaraskan dengan dashboard.html */
  .assa-blue { color: #1E3A8A; }
  .bg-assa-blue { background-color: #1E3A8A; }
  .assa-light { background-color: #F8FAFC; }

  .gradient-bg {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
  }

  .card-hover {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
  }

  .card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }

  .event-card {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
    border: 1px solid #dbeafe;
  }

  .rounded-2xl { border-radius: 1rem; }
  .rounded-xl { border-radius: 0.75rem; }
  .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
  .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2); }
  .bg-opacity-10 { background-color: rgba(255, 255, 255, 0.1); }

  .quick-action-btn {
    transition: all 0.3s ease;
    transform: scale(1);
  }

  .quick-action-btn:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
  }

  .quick-action-btn .icon-container {
    transition: transform 0.3s ease;
  }

  .quick-action-btn:hover .icon-container {
    transform: scale(1.1);
  }

  .stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
  }

  .announcement-card {
    padding: 1.25rem;
    border-radius: 0.75rem;
    border-left: 4px solid;
  }

  .announcement-primary {
    background: linear-gradient(to right, #eff6ff, #dbeafe);
    border-left-color: #1E3A8A;
  }

  .announcement-success {
    background: linear-gradient(to right, #f0fdf4, #dcfce7);
    border-left-color: #10b981;
  }

  .announcement-purple {
    background: linear-gradient(to right, #faf5ff, #f3e8ff);
    border-left-color: #8b5cf6;
  }

  .badge-rounded {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .text-xs { font-size: 0.75rem; }
  .text-sm { font-size: 0.875rem; }
  .tracking-wide { letter-spacing: 0.025em; }
  .uppercase { text-transform: uppercase; }
</style>

<div class="container-fluid p-0">
  {{-- Welcome Section --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h2 class="fw-bold mb-2 fs-2">Selamat Datang, Rizka! 👋</h2>
            <p class="mb-0 fs-5" style="color: #bfdbfe;">Mari berkontribusi untuk Indonesia Emas 2045</p>
          </div>
          <div class="d-none d-md-block">
            <div class="bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 96px; height: 96px; background-color: rgba(255, 255, 255, 0.2);">
              <i class="fas fa-rocket" style="font-size: 2.5rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Quick Stats --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Status Beasiswa</p>
            <p class="fw-bold mb-1 text-success" style="font-size: 2rem;">Aktif</p>
            <p class="text-sm text-muted">Beasiswa Prestasi 2024</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(16, 185, 129, 0.1);">
            <i class="fas fa-graduation-cap text-success" style="font-size: 1.5rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Jumlah Prestasi</p>
            <p class="fw-bold mb-1 assa-blue" style="font-size: 2rem;">15</p>
            <p class="text-sm text-success">↑ 3 prestasi baru</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(30, 58, 138, 0.1);">
            <i class="fas fa-trophy assa-blue" style="font-size: 1.5rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Kegiatan Diikuti</p>
            <p class="fw-bold mb-1" style="font-size: 2rem; color: #8b5cf6;">12</p>
            <p class="text-sm text-muted">Bulan ini: 2 kegiatan</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(139, 92, 246, 0.1);">
            <i class="fas fa-calendar-check" style="font-size: 1.5rem; color: #8b5cf6;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    {{-- Pengumuman --}}
    <div class="col-xl-8">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-bold mb-0 fs-5">📢 Pengumuman Terbaru</h3>
          <button class="btn assa-blue text-sm fw-medium" style="background: none; border: none;">Lihat Semua</button>
        </div>
        <div class="vstack gap-3">
          <div class="announcement-card announcement-primary">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-fill">
                <h4 class="fw-bold mb-1">🎓 Beasiswa Prestasi 2024 Dibuka!</h4>
                <p class="text-sm text-muted mb-2">Pendaftaran beasiswa untuk mahasiswa berprestasi dibuka hingga 31 Desember 2024. Dapatkan bantuan pendidikan hingga Rp 10.000.000.</p>
                <div class="d-flex align-items-center gap-3">
                  <span class="text-xs fw-semibold assa-blue">2 hari yang lalu</span>
                  <span class="badge-rounded bg-assa-blue text-white">Penting</span>
                </div>
              </div>
            </div>
          </div>

          <div class="announcement-card announcement-success">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-fill">
                <h4 class="fw-bold mb-1">⚽ Futsal ASSA x UHB - Event Terbaru!</h4>
                <p class="text-sm text-muted mb-2">Turnamen futsal persahabatan antara ASSA dan UHB. Daftar sekarang dan tunjukkan kemampuan olahragamu!</p>
                <div class="d-flex align-items-center gap-3">
                  <span class="text-xs fw-semibold text-success">1 hari yang lalu</span>
                  <span class="badge-rounded bg-success text-white">Baru</span>
                </div>
              </div>
            </div>
          </div>

          <div class="announcement-card announcement-purple">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-fill">
                <h4 class="fw-bold mb-1">🏛️ Kunjungan ke Istana Kepresidenan</h4>
                <p class="text-sm text-muted mb-2">Kesempatan langka untuk mengunjungi Istana Kepresidenan Yogyakarta dan belajar tentang sejarah kepemimpinan Indonesia.</p>
                <div class="d-flex align-items-center gap-3">
                  <span class="text-xs fw-semibold" style="color: #8b5cf6;">5 hari yang lalu</span>
                  <span class="badge-rounded text-white" style="background-color: #8b5cf6;">Selesai</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Kegiatan Mendatang --}}
    <div class="col-xl-4">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <h3 class="fw-bold mb-4 fs-5">📅 Kegiatan Mendatang</h3>
        <div class="vstack gap-3">
          <div class="event-card d-flex align-items-center gap-3 p-3 rounded-xl">
            <div class="gradient-bg text-white rounded-xl d-flex flex-column align-items-center justify-content-center"
                 style="width: 56px; height: 56px; line-height: 1.2;">
              <span class="fw-bold" style="font-size: 1.125rem;">18</span>
              <span class="text-xs">Jan</span>
            </div>
            <div class="flex-fill">
              <p class="fw-bold mb-0 text-sm">Seminar Pemuda Produktif</p>
              <p class="text-xs text-muted mb-1">Menuju Indonesia Emas 2045</p>
              <p class="text-xs assa-blue fw-medium">09:00 WIB</p>
            </div>
          </div>

          <div class="event-card d-flex align-items-center gap-3 p-3 rounded-xl" style="border-color: #d1fae5;">
            <div class="bg-success text-white rounded-xl d-flex flex-column align-items-center justify-content-center"
                 style="width: 56px; height: 56px; line-height: 1.2;">
              <span class="fw-bold" style="font-size: 1.125rem;">25</span>
              <span class="text-xs">Jan</span>
            </div>
            <div class="flex-fill">
              <p class="fw-bold mb-0 text-sm">Outbound ASSA</p>
              <p class="text-xs text-muted mb-1">Team Building & Leadership</p>
              <p class="text-xs text-success fw-medium">07:00 WIB</p>
            </div>
          </div>

          <div class="event-card d-flex align-items-center gap-3 p-3 rounded-xl" style="border-color: #e9d5ff;">
            <div class="text-white rounded-xl d-flex flex-column align-items-center justify-content-center"
                 style="width: 56px; height: 56px; line-height: 1.2; background-color: #8b5cf6;">
              <span class="fw-bold" style="font-size: 1.125rem;">02</span>
              <span class="text-xs">Feb</span>
            </div>
            <div class="flex-fill">
              <p class="fw-bold mb-0 text-sm">Kunjungan Kominfo</p>
              <p class="text-xs text-muted mb-1">Kantor Komunikasi Kepresidenan</p>
              <p class="text-xs fw-medium" style="color: #8b5cf6;">10:00 WIB</p>
            </div>
          </div>
        </div>

        <button class="btn btn-outline-primary btn-sm w-100 mt-4 text-sm fw-medium assa-blue"
                style="border-color: #1E3A8A;">
          Lihat Semua Kegiatan
        </button>
      </div>
    </div>
  </div>

  {{-- Quick Actions --}}
  <div class="row">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg p-4 border">
        <h3 class="fw-bold mb-4 fs-5">⚡ Aksi Cepat</h3>
        <div class="row g-4">
          <div class="col-lg-4 col-md-6">
            <button class="quick-action-btn w-100 d-flex flex-column align-items-center gap-3 p-4 gradient-bg text-white rounded-2xl border-0">
              <div class="icon-container d-flex align-items-center justify-content-center rounded-2xl bg-opacity-20"
                   style="width: 64px; height: 64px; background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-graduation-cap" style="font-size: 1.5rem;"></i>
              </div>
              <div class="text-center">
                <span class="fw-bold" style="font-size: 1.125rem;">Apply Beasiswa</span>
                <p class="text-sm mb-0" style="color: #bfdbfe;">Ajukan permohonan beasiswa</p>
              </div>
            </button>
          </div>

          <div class="col-lg-4 col-md-6">
            <button class="quick-action-btn w-100 d-flex flex-column align-items-center gap-3 p-4 bg-success text-white rounded-2xl border-0">
              <div class="icon-container d-flex align-items-center justify-content-center rounded-2xl bg-opacity-20"
                   style="width: 64px; height: 64px; background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-handshake" style="font-size: 1.5rem;"></i>
              </div>
              <div class="text-center">
                <span class="fw-bold" style="font-size: 1.125rem;">Ajukan Kerjasama</span>
                <p class="text-sm mb-0" style="color: #bbf7d0;">Proposal kerjasama baru</p>
              </div>
            </button>
          </div>

          <div class="col-lg-4 col-md-6">
            <button class="quick-action-btn w-100 d-flex flex-column align-items-center gap-3 p-4 text-white rounded-2xl border-0"
                    style="background-color: #8b5cf6;">
              <div class="icon-container d-flex align-items-center justify-content-center rounded-2xl bg-opacity-20"
                   style="width: 64px; height: 64px; background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-trophy" style="font-size: 1.5rem;"></i>
              </div>
              <div class="text-center">
                <span class="fw-bold" style="font-size: 1.125rem;">Setor Prestasi</span>
                <p class="text-sm mb-0" style="color: #ddd6fe;">Upload pencapaian terbaru</p>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Kegiatan yang Diikuti Section --}}
  <div class="row mt-4">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-bold mb-0 fs-5">🎯 Kegiatan yang Diikuti</h3>
          <div class="d-flex align-items-center gap-2">
            <span class="badge-rounded bg-assa-blue text-white">
              {{ isset($stats) ? $stats['total_activities'] : auth()->user()->activities()->count() }} Total
            </span>
            <button class="btn btn-outline-secondary btn-sm fw-medium" style="border-radius: 0.5rem;">
              Lihat Semua
            </button>
          </div>
        </div>

        @if($userActivities->count() > 0)
          <div class="row g-3">
            @foreach($userActivities as $activity)
              <div class="col-md-6">
                <div class="event-card p-3 rounded-xl card-hover h-100">
                  <div class="d-flex align-items-start gap-3">
                    {{-- Status Badge --}}
                    <div class="d-flex flex-column align-items-center">
                      @if($activity->status === 'upcoming')
                        <div class="gradient-bg text-white rounded-xl d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                          <i class="fas fa-clock" style="font-size: 1.25rem;"></i>
                        </div>
                      @elseif($activity->status === 'ongoing')
                        <div class="bg-success text-white rounded-xl d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                          <i class="fas fa-play" style="font-size: 1.25rem;"></i>
                        </div>
                      @else
                        <div class="text-white rounded-xl d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px; background-color: #6b7280;">
                          <i class="fas fa-check" style="font-size: 1.25rem;"></i>
                        </div>
                      @endif
                    </div>

                    {{-- Activity Content --}}
                    <div class="flex-fill">
                      <h5 class="fw-bold mb-1 text-sm">{{ $activity->title }}</h5>
                      <p class="text-xs text-muted mb-2 line-clamp-2">{{ Str::limit($activity->description, 100) }}</p>

                      {{-- Activity Meta --}}
                      <div class="d-flex flex-wrap gap-2 mb-2">
                        @if($activity->start_at)
                          <span class="text-xs assa-blue fw-medium">
                            <i class="fas fa-calendar me-1"></i>{{ $activity->start_at->format('d M Y') }}
                          </span>
                        @endif
                        @if($activity->location)
                          <span class="text-xs text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($activity->location, 20) }}
                          </span>
                        @endif
                      </div>

                      {{-- Participation Status --}}
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          @if($activity->pivot->status === 'registered')
                            <span class="badge-rounded" style="background-color: #fef3c7; color: #92400e;">Terdaftar</span>
                          @elseif($activity->pivot->status === 'attended')
                            <span class="badge-rounded" style="background-color: #dcfce7; color: #166534;">Hadir</span>
                          @elseif($activity->pivot->status === 'absent')
                            <span class="badge-rounded" style="background-color: #fee2e2; color: #991b1b;">Tidak Hadir</span>
                          @else
                            <span class="badge-rounded" style="background-color: #f3f4f6; color: #374151;">{{ ucfirst($activity->pivot->status) }}</span>
                          @endif
                        </div>

                        <span class="text-xs text-muted">
                          Daftar: {{ $activity->pivot->registered_at ? $activity->pivot->registered_at->format('d M Y') : '-' }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          {{-- Empty State --}}
          <div class="text-center py-5">
            <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                 style="width: 80px; height: 80px; background-color: #f3f4f6;">
              <i class="fas fa-calendar-times text-muted" style="font-size: 2rem;"></i>
            </div>
            <h4 class="fw-bold text-muted mb-2">Belum Ada Kegiatan</h4>
            <p class="text-muted mb-4">Anda belum mengikuti kegiatan apapun. Mari bergabung dengan kegiatan ASSA!</p>
            <button class="btn bg-assa-blue text-white fw-medium" style="border-radius: 0.5rem;">
              Lihat Kegiatan Tersedia
            </button>
          </div>
        @endif

        {{-- Summary Stats --}}
        @if($userActivities->count() > 0)
          <div class="border-top mt-4 pt-4">
            <div class="row text-center">
              <div class="col-4">
                <div class="text-center">
                  <p class="fw-bold mb-0 assa-blue" style="font-size: 1.5rem;">
                    {{ isset($stats) ? $stats['total_activities'] : auth()->user()->activities()->count() }}
                  </p>
                  <p class="text-xs text-muted mb-0">Total Kegiatan</p>
                </div>
              </div>
              <div class="col-4">
                <div class="text-center">
                  <p class="fw-bold mb-0 text-success" style="font-size: 1.5rem;">
                    {{ isset($stats) ? $stats['attended_activities'] : auth()->user()->activities()->wherePivot('status', 'attended')->count() }}
                  </p>
                  <p class="text-xs text-muted mb-0">Sudah Hadir</p>
                </div>
              </div>
              <div class="col-4">
                <div class="text-center">
                  <p class="fw-bold mb-0" style="font-size: 1.5rem; color: #8b5cf6;">
                    {{ isset($stats) ? $stats['upcoming_activities'] : auth()->user()->activities()->whereHas('activity', function($q) { $q->where('start_at', '>', now()); })->count() }}
                  </p>
                  <p class="text-xs text-muted mb-0">Akan Datang</p>
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
