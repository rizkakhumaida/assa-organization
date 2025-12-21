@extends('layouts.app')

@section('content')
@php
    use App\Models\Achievement;
    use Illuminate\Support\Str;

    $user = auth()->user();

    // ====== Hitung Prestasi (real dari tabel achievements) ======
    $prestasiCount = 0;

    try {
        if (\Schema::hasColumn('achievements', 'user_id')) {
            $prestasiCount = Achievement::where('user_id', $user->id)->count();
        } else {
            $prestasiCount = Achievement::where('peserta', $user->name)->count();
        }
    } catch (\Throwable $e) {
        $prestasiCount = Achievement::where('peserta', $user->name)->count();
    }

    // ====== Hitung Kegiatan Diikuti (real) ======
    $kegiatanCount = $user->activities()->count();

    $kegiatanBulanIni = $user->activities()
        ->whereMonth('start_at', now()->month)
        ->whereYear('start_at', now()->year)
        ->count();

    // ====== List kegiatan (ambil dari controller jika ada, kalau tidak ambil di view) ======
    $userActivities = $userActivities ?? $user->activities()
        ->orderByDesc('start_at')
        ->take(6)
        ->get();

    // ====== Summary stats ======
    $attendedCount = $user->activities()->wherePivot('status', 'attended')->count();
    $upcomingCount = $user->activities()->where('start_at', '>', now())->count();
@endphp

<style>
  .assa-blue { color: #1E3A8A; }
  .bg-assa-blue { background-color: #1E3A8A; }
  .gradient-bg { background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); }

  .rounded-2xl { border-radius: 1rem; }
  .rounded-xl { border-radius: 0.75rem; }
  .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05); }

  .card-hover { transition: all .25s ease; border: 1px solid #e5e7eb; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,.08); }

  .stat-card { background: #fff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #e5e7eb; }

  .event-card {
    background: linear-gradient(135deg, rgba(30,58,138,.10) 0%, rgba(59,130,246,.05) 100%);
    border: 1px solid #dbeafe;
  }

  .announcement-card { padding: 1.25rem; border-radius: .75rem; border-left: 4px solid; }
  .announcement-primary { background: linear-gradient(to right, #eff6ff, #dbeafe); border-left-color:#1E3A8A; }
  .announcement-success { background: linear-gradient(to right, #f0fdf4, #dcfce7); border-left-color:#10b981; }
  .announcement-purple  { background: linear-gradient(to right, #faf5ff, #f3e8ff); border-left-color:#8b5cf6; }

  .badge-rounded { padding: .25rem .5rem; border-radius: 9999px; font-size: .75rem; font-weight: 600; }

  .text-xs { font-size: .75rem; }
  .text-sm { font-size: .875rem; }

  /* Quick action: anchor looks like button */
  .quick-action-btn {
    transition: all .25s ease;
    text-decoration: none;
    display: flex;
  }
  .quick-action-btn:hover { transform: translateY(-4px) scale(1.01); box-shadow: 0 15px 30px rgba(0,0,0,.12); }
  .quick-action-btn .icon-container { transition: transform .25s ease; }
  .quick-action-btn:hover .icon-container { transform: scale(1.08); }
</style>

<div class="container-fluid p-0">

  {{-- Welcome --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h2 class="fw-bold mb-2 fs-2">Selamat Datang, {{ $user->name ?? 'Anggota' }}!</h2>
            <p class="mb-0 fs-5" style="color:#bfdbfe;">Mari berkontribusi untuk Indonesia Emas 2045</p>
          </div>
          <div class="d-none d-md-block">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:96px;height:96px;background-color:rgba(255,255,255,.2);">
              <i class="fas fa-rocket" style="font-size:2.5rem;"></i>
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
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm">Status Beasiswa</p>
            <p class="fw-bold mb-1 text-success" style="font-size:2rem;">Aktif</p>
            <p class="text-sm text-muted mb-0">Beasiswa Prestasi</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width:64px;height:64px;background-color:rgba(16,185,129,.1);">
            <i class="fas fa-graduation-cap text-success" style="font-size:1.5rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm">Jumlah Prestasi</p>
            <p class="fw-bold mb-1 assa-blue" style="font-size:2rem;">{{ $prestasiCount }}</p>
            <p class="text-sm text-muted mb-0">Total prestasi tersimpan</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width:64px;height:64px;background-color:rgba(30,58,138,.1);">
            <i class="fas fa-trophy assa-blue" style="font-size:1.5rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm">Kegiatan Diikuti</p>
            <p class="fw-bold mb-1" style="font-size:2rem;color:#8b5cf6;">{{ $kegiatanCount }}</p>
            <p class="text-sm text-muted mb-0">Bulan ini: {{ $kegiatanBulanIni }} kegiatan</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width:64px;height:64px;background-color:rgba(139,92,246,.1);">
            <i class="fas fa-calendar-check" style="font-size:1.5rem;color:#8b5cf6;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Pengumuman + Kegiatan Mendatang --}}
  <div class="row g-4 mb-4">
    <div class="col-xl-8">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-bold mb-0 fs-5">📢 Pengumuman Terbaru</h3>
          <a href="#" class="btn assa-blue text-sm fw-medium" style="background:none;border:none;">Lihat Semua</a>
        </div>

        <div class="vstack gap-3">
          <div class="announcement-card announcement-primary">
            <h4 class="fw-bold mb-1">🎓 Beasiswa Prestasi Dibuka!</h4>
            <p class="text-sm text-muted mb-2">Silakan cek informasi beasiswa dan lengkapi persyaratan sebelum batas waktu pendaftaran.</p>
            <div class="d-flex align-items-center gap-3">
              <span class="text-xs fw-semibold assa-blue">Update terbaru</span>
              <span class="badge-rounded bg-assa-blue text-white">Penting</span>
            </div>
          </div>

          <div class="announcement-card announcement-success">
            <h4 class="fw-bold mb-1">⚽ Event Olahraga</h4>
            <p class="text-sm text-muted mb-2">Daftarkan tim Anda dan ikuti kegiatan untuk memperkuat kolaborasi antar anggota.</p>
            <div class="d-flex align-items-center gap-3">
              <span class="text-xs fw-semibold text-success">Update terbaru</span>
              <span class="badge-rounded bg-success text-white">Baru</span>
            </div>
          </div>

          <div class="announcement-card announcement-purple">
            <h4 class="fw-bold mb-1">🏛️ Kunjungan Instansi</h4>
            <p class="text-sm text-muted mb-2">Kegiatan kunjungan terjadwal. Pastikan Anda memantau jadwal kegiatan pada menu Kegiatan.</p>
            <div class="d-flex align-items-center gap-3">
              <span class="text-xs fw-semibold" style="color:#8b5cf6;">Update terbaru</span>
              <span class="badge-rounded text-white" style="background-color:#8b5cf6;">Info</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <h3 class="fw-bold mb-4 fs-5">📅 Kegiatan Mendatang</h3>

        {{-- Contoh static --}}
        <div class="vstack gap-3">
          <div class="event-card d-flex align-items-center gap-3 p-3 rounded-xl">
            <div class="gradient-bg text-white rounded-xl d-flex flex-column align-items-center justify-content-center"
                 style="width:56px;height:56px;line-height:1.2;">
              <span class="fw-bold" style="font-size:1.125rem;">18</span>
              <span class="text-xs">Jan</span>
            </div>
            <div class="flex-fill">
              <p class="fw-bold mb-0 text-sm">Seminar Pemuda Produktif</p>
              <p class="text-xs text-muted mb-1">Menuju Indonesia Emas</p>
              <p class="text-xs assa-blue fw-medium mb-0">09:00 WIB</p>
            </div>
          </div>

          <div class="event-card d-flex align-items-center gap-3 p-3 rounded-xl" style="border-color:#d1fae5;">
            <div class="bg-success text-white rounded-xl d-flex flex-column align-items-center justify-content-center"
                 style="width:56px;height:56px;line-height:1.2;">
              <span class="fw-bold" style="font-size:1.125rem;">25</span>
              <span class="text-xs">Jan</span>
            </div>
            <div class="flex-fill">
              <p class="fw-bold mb-0 text-sm">Outbound ASSA</p>
              <p class="text-xs text-muted mb-1">Team Building</p>
              <p class="text-xs text-success fw-medium mb-0">07:00 WIB</p>
            </div>
          </div>

          <div class="event-card d-flex align-items-center gap-3 p-3 rounded-xl" style="border-color:#e9d5ff;">
            <div class="text-white rounded-xl d-flex flex-column align-items-center justify-content-center"
                 style="width:56px;height:56px;line-height:1.2;background-color:#8b5cf6;">
              <span class="fw-bold" style="font-size:1.125rem;">02</span>
              <span class="text-xs">Feb</span>
            </div>
            <div class="flex-fill">
              <p class="fw-bold mb-0 text-sm">Kunjungan Kominfo</p>
              <p class="text-xs text-muted mb-1">Koordinasi Program</p>
              <p class="text-xs fw-medium mb-0" style="color:#8b5cf6;">10:00 WIB</p>
            </div>
          </div>
        </div>

        {{-- Route sesuai Anda --}}
        <a href="{{ route('anggota.activities.index') }}"
           class="btn btn-outline-primary btn-sm w-100 mt-4 text-sm fw-medium assa-blue"
           style="border-color:#1E3A8A;">
          Lihat Semua Kegiatan
        </a>
      </div>
    </div>
  </div>

  {{-- Quick Actions (route sesuai Anda) --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg p-4 border">
        <h3 class="fw-bold mb-4 fs-5">⚡ Aksi Cepat</h3>

        <div class="row g-4">
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('anggota.scholarship.index') }}"
               class="quick-action-btn w-100 flex-column align-items-center gap-3 p-4 gradient-bg text-white rounded-2xl border-0">
              <div class="icon-container d-flex align-items-center justify-content-center rounded-2xl"
                   style="width:64px;height:64px;background-color:rgba(255,255,255,.2);">
                <i class="fas fa-graduation-cap" style="font-size:1.5rem;"></i>
              </div>
              <div class="text-center">
                <span class="fw-bold" style="font-size:1.125rem;">Apply Beasiswa</span>
                <p class="text-sm mb-0" style="color:#bfdbfe;">Ajukan permohonan beasiswa</p>
              </div>
            </a>
          </div>

          <div class="col-lg-4 col-md-6">
            <a href="{{ route('anggota.partnerships.index') }}"
               class="quick-action-btn w-100 flex-column align-items-center gap-3 p-4 bg-success text-white rounded-2xl border-0">
              <div class="icon-container d-flex align-items-center justify-content-center rounded-2xl"
                   style="width:64px;height:64px;background-color:rgba(255,255,255,.2);">
                <i class="fas fa-handshake" style="font-size:1.5rem;"></i>
              </div>
              <div class="text-center">
                <span class="fw-bold" style="font-size:1.125rem;">Ajukan Kerjasama</span>
                <p class="text-sm mb-0" style="color:#bbf7d0;">Proposal kerjasama baru</p>
              </div>
            </a>
          </div>

          <div class="col-lg-4 col-md-6">
            <a href="{{ route('anggota.achievements.create') }}"
               class="quick-action-btn w-100 flex-column align-items-center gap-3 p-4 text-white rounded-2xl border-0"
               style="background-color:#8b5cf6;">
              <div class="icon-container d-flex align-items-center justify-content-center rounded-2xl"
                   style="width:64px;height:64px;background-color:rgba(255,255,255,.2);">
                <i class="fas fa-trophy" style="font-size:1.5rem;"></i>
              </div>
              <div class="text-center">
                <span class="fw-bold" style="font-size:1.125rem;">Setor Prestasi</span>
                <p class="text-sm mb-0" style="color:#ddd6fe;">Upload pencapaian terbaru</p>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Kegiatan yang Diikuti --}}
  <div class="row">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-bold mb-0 fs-5">🎯 Kegiatan yang Diikuti</h3>
          <div class="d-flex align-items-center gap-2">
            <span class="badge-rounded bg-assa-blue text-white">{{ $kegiatanCount }} Total</span>
            <a href="{{ route('anggota.activities.index') }}"
               class="btn btn-outline-secondary btn-sm fw-medium"
               style="border-radius:.5rem;">
              Lihat Semua
            </a>
          </div>
        </div>

        @if ($userActivities->count() > 0)
          <div class="row g-3">
            @foreach ($userActivities as $activity)
              <div class="col-md-6">
                <div class="event-card p-3 rounded-xl card-hover h-100">
                  <div class="d-flex align-items-start gap-3">

                    {{-- Icon status kegiatan --}}
                    <div class="d-flex flex-column align-items-center">
                      @if($activity->status === 'upcoming')
                        <div class="gradient-bg text-white rounded-xl d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                          <i class="fas fa-clock" style="font-size:1.25rem;"></i>
                        </div>
                      @elseif($activity->status === 'ongoing')
                        <div class="bg-success text-white rounded-xl d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                          <i class="fas fa-play" style="font-size:1.25rem;"></i>
                        </div>
                      @else
                        <div class="text-white rounded-xl d-flex align-items-center justify-content-center" style="width:48px;height:48px;background-color:#6b7280;">
                          <i class="fas fa-check" style="font-size:1.25rem;"></i>
                        </div>
                      @endif
                    </div>

                    <div class="flex-fill">
                      <h5 class="fw-bold mb-1 text-sm">{{ $activity->title }}</h5>
                      <p class="text-xs text-muted mb-2">{{ Str::limit($activity->description, 100) }}</p>

                      <div class="d-flex flex-wrap gap-2 mb-2">
                        @if($activity->start_at)
                          <span class="text-xs assa-blue fw-medium">
                            <i class="fas fa-calendar me-1"></i>{{ $activity->start_at->format('d M Y') }}
                          </span>
                        @endif
                        @if($activity->location)
                          <span class="text-xs text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($activity->location, 24) }}
                          </span>
                        @endif
                      </div>

                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          @php $p = strtolower($activity->pivot->status ?? 'registered'); @endphp

                          @if($p === 'registered')
                            <span class="badge-rounded" style="background-color:#fef3c7;color:#92400e;">Terdaftar</span>
                          @elseif($p === 'attended')
                            <span class="badge-rounded" style="background-color:#dcfce7;color:#166534;">Hadir</span>
                          @elseif($p === 'absent')
                            <span class="badge-rounded" style="background-color:#fee2e2;color:#991b1b;">Tidak Hadir</span>
                          @else
                            <span class="badge-rounded" style="background-color:#f3f4f6;color:#374151;">{{ ucfirst($p) }}</span>
                          @endif
                        </div>

                        <span class="text-xs text-muted">
                          Daftar:
                          @if(!empty($activity->pivot->registered_at))
                            {{ \Carbon\Carbon::parse($activity->pivot->registered_at)->format('d M Y') }}
                          @else
                            -
                          @endif
                        </span>
                      </div>

                    </div>

                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Summary --}}
          <div class="border-top mt-4 pt-4">
            <div class="row text-center">
              <div class="col-4">
                <p class="fw-bold mb-0 assa-blue" style="font-size:1.5rem;">{{ $kegiatanCount }}</p>
                <p class="text-xs text-muted mb-0">Total Kegiatan</p>
              </div>
              <div class="col-4">
                <p class="fw-bold mb-0 text-success" style="font-size:1.5rem;">{{ $attendedCount }}</p>
                <p class="text-xs text-muted mb-0">Sudah Hadir</p>
              </div>
              <div class="col-4">
                <p class="fw-bold mb-0" style="font-size:1.5rem;color:#8b5cf6;">{{ $upcomingCount }}</p>
                <p class="text-xs text-muted mb-0">Akan Datang</p>
              </div>
            </div>
          </div>

        @else
          <div class="text-center py-5">
            <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                 style="width:80px;height:80px;background-color:#f3f4f6;">
              <i class="fas fa-calendar-times text-muted" style="font-size:2rem;"></i>
            </div>
            <h4 class="fw-bold text-muted mb-2">Belum Ada Kegiatan</h4>
            <p class="text-muted mb-4">Anda belum mengikuti kegiatan apapun. Mari bergabung dengan kegiatan ASSA.</p>
            <a href="{{ route('anggota.activities.index') }}"
               class="btn bg-assa-blue text-white fw-medium"
               style="border-radius:.5rem;">
              Lihat Kegiatan Tersedia
            </a>
          </div>
        @endif

      </div>
    </div>
  </div>

</div>
@endsection
