@extends('layouts.app')

@section('content')
<style>
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

  .rounded-2xl { border-radius: 1rem; }
  .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }

  .stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
  }

  .badge-rounded {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
  }

  .text-xs { font-size: 0.75rem; }
  .text-sm { font-size: 0.875rem; }
  .tracking-wide { letter-spacing: 0.025em; }
  .uppercase { text-transform: uppercase; }

  .chart-container {
    height: 320px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding: 0 1rem;
    gap: 0.75rem;
  }

  .chart-bar {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 48px;
  }

  .chart-bar-fill {
    width: 100%;
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    border-radius: 0.5rem 0.5rem 0 0;
    transition: height .25s ease;
  }
</style>

@php
  // ======== TREND (dinamis dari controller) ========
  $trendArr = (isset($trend) && is_iterable($trend)) ? collect($trend)->toArray() : [];
  $trendMax = max([1, ...array_values($trendArr ?: [1])]);

  $months = [
    1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
    7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'
  ];

  // ======== helper badge status ========
  $badgeForStatus = function ($status) {
    $s = strtolower((string)$status);

    if (in_array($s, ['pending', 'submitted'])) {
      return ['bg' => '#fef3c7', 'fg' => '#92400e', 'text' => 'Pending Review'];
    }
    if (in_array($s, ['approved', 'verified', 'accepted'])) {
      return ['bg' => '#dcfce7', 'fg' => '#166534', 'text' => 'Approved'];
    }
    if (in_array($s, ['rejected', 'declined'])) {
      return ['bg' => '#fee2e2', 'fg' => '#991b1b', 'text' => 'Rejected'];
    }
    return ['bg' => '#e5e7eb', 'fg' => '#374151', 'text' => $status ?: 'Unknown'];
  };

  // Pagination info
  $pageFirst = (isset($recent_applications) && method_exists($recent_applications, 'firstItem')) ? $recent_applications->firstItem() : null;
  $pageLast  = (isset($recent_applications) && method_exists($recent_applications, 'lastItem')) ? $recent_applications->lastItem() : null;
  $pageTotal = (isset($recent_applications) && method_exists($recent_applications, 'total')) ? $recent_applications->total() : null;

  // Export/Filter query string
  $currentUrl = url()->current();
  $qs = request()->query();
@endphp

<div class="container-fluid p-0">

  {{-- HEADER --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h2 class="fw-bold mb-2 fs-2">Dashboard Admin</h2>
            <p class="mb-0 fs-5" style="color: #bfdbfe;">Kelola dan pantau aktivitas ASSA Organization</p>
          </div>
          <div class="d-none d-md-block">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 96px; height: 96px; background-color: rgba(255, 255, 255, 0.2);">
              <i class="fas fa-chart-line" style="font-size: 2.5rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- STATISTIK (REAL) --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Beasiswa Diajukan</p>
            <p class="fw-bold mb-1 assa-blue" style="font-size: 2rem;">
              {{ $stats['scholarships_pending'] ?? 0 }}
            </p>
            <p class="text-sm text-muted mb-0">Berdasarkan data beasiswa (real)</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(30, 58, 138, 0.1);">
            <i class="fas fa-graduation-cap assa-blue" style="font-size: 1.5rem;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Jumlah Kerja Sama</p>
            <p class="fw-bold mb-1" style="font-size: 2rem; color: #16a34a;">
              {{ $stats['partnerships_total'] ?? 0 }}
            </p>
            <p class="text-sm text-muted mb-0">Berdasarkan data kerja sama (real)</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(16, 185, 129, 0.1);">
            <i class="fas fa-handshake" style="font-size: 1.5rem; color:#16a34a;"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Total Prestasi</p>
            <p class="fw-bold mb-1" style="font-size: 2rem; color: #8b5cf6;">
              {{ $stats['achievements_total'] ?? 0 }}
            </p>
            <p class="text-sm text-muted mb-0">Berdasarkan data prestasi (real)</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(139, 92, 246, 0.1);">
            <i class="fas fa-trophy" style="font-size: 1.5rem; color: #8b5cf6;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- TREND + NOTIF --}}
  <div class="row g-4 mb-4">
    <div class="col-xl-6">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <h3 class="fw-bold mb-4 fs-5">📊 Trend Pengajuan Bulanan ({{ now()->year }})</h3>

        <div class="chart-container">
          @foreach($months as $m => $label)
            @php
              $count = (int)($trendArr[$m] ?? 0);
              $height = $trendMax > 0 ? max(16, (int)round(($count / $trendMax) * 240)) : 16;
            @endphp
            <div class="chart-bar">
              <div class="chart-bar-fill" style="height: {{ $height }}px;"></div>
              <span class="text-xs text-muted mt-3 fw-medium">{{ $label }}</span>
              <span class="text-xs text-muted">{{ $count }}</span>
            </div>
          @endforeach
        </div>

        <small class="text-muted d-block mt-3">
          Sumber: gabungan pengajuan Beasiswa + Kerja Sama + Prestasi (real).
        </small>
      </div>
    </div>

    <div class="col-xl-6">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <h3 class="fw-bold mb-4 fs-5">🔔 Notifikasi Admin</h3>

        <div class="alert alert-warning mb-3" style="border-left:4px solid #f59e0b;">
          <div class="fw-bold mb-1">Review Diperlukan</div>
          <div class="text-muted text-sm">Pantau pengajuan terbaru dan lakukan verifikasi sesuai kebutuhan.</div>
        </div>

        <div class="alert alert-primary mb-3" style="border-left:4px solid #1E3A8A;">
          <div class="fw-bold mb-1">Ringkasan</div>
          <div class="text-muted text-sm">Statistik dan trend di halaman ini mengambil data real dari database.</div>
        </div>

        <div class="alert alert-success mb-0" style="border-left:4px solid #10b981;">
          <div class="fw-bold mb-1">Konsistensi Data</div>
          <div class="text-muted text-sm">Gunakan menu Poin/Beasiswa/Kerja Sama untuk detail masing-masing modul.</div>
        </div>
      </div>
    </div>
  </div>

  {{-- PENGAJUAN TERBARU --}}
  <div class="row">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">

        <div class="p-4 border-bottom" style="background-color: #f9fafb;">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h3 class="fw-bold mb-0 fs-5">📋 Pengajuan Terbaru</h3>

            <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
              <a class="btn bg-assa-blue text-white btn-sm fw-medium" style="border-radius: 0.5rem;"
                 href="{{ $currentUrl.'?'.http_build_query(array_merge($qs, ['export' => 'csv'])) }}">
                Export Data
              </a>

              <form method="GET" action="{{ $currentUrl }}" class="d-flex gap-2 flex-wrap">
                <select name="type" class="form-select form-select-sm" style="width: 150px;">
                  <option value="">Semua Jenis</option>
                  <option value="Beasiswa" {{ request('type')=='Beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                  <option value="Kerja Sama" {{ request('type')=='Kerja Sama' ? 'selected' : '' }}>Kerja Sama</option>
                  <option value="Prestasi" {{ request('type')=='Prestasi' ? 'selected' : '' }}>Prestasi</option>
                </select>

                <select name="status" class="form-select form-select-sm" style="width: 150px;">
                  <option value="">Semua Status</option>
                  <option value="pending"   {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                  <option value="submitted" {{ request('status')=='submitted' ? 'selected' : '' }}>Submitted</option>
                  <option value="approved"  {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                  <option value="verified"  {{ request('status')=='verified' ? 'selected' : '' }}>Verified</option>
                  <option value="rejected"  {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <button class="btn btn-outline-secondary btn-sm fw-medium" style="border-radius: 0.5rem;" type="submit">
                  Filter
                </button>

                @if(request()->filled('type') || request()->filled('status'))
                  <a href="{{ $currentUrl }}" class="btn btn-light btn-sm" style="border-radius: 0.5rem;">
                    Reset
                  </a>
                @endif
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-borderless mb-0">
            <thead style="background-color: #f9fafb;">
              <tr>
                <th class="px-4 py-3 text-muted text-uppercase fw-bold text-xs tracking-wide">Nama Anggota</th>
                <th class="px-4 py-3 text-muted text-uppercase fw-bold text-xs tracking-wide">Jenis Pengajuan</th>
                <th class="px-4 py-3 text-muted text-uppercase fw-bold text-xs tracking-wide">Tanggal</th>
                <th class="px-4 py-3 text-muted text-uppercase fw-bold text-xs tracking-wide">Status</th>
                <th class="px-4 py-3 text-muted text-uppercase fw-bold text-xs tracking-wide">Aksi</th>
              </tr>
            </thead>

            <tbody>
              @if(isset($recent_applications) && $recent_applications->count() > 0)
                @foreach($recent_applications as $application)
                  @php
                    // ✅ Ambil langsung dari hasil UNION controller (name/email sudah dikirim)
                    $name  = $application->name ?? 'Unknown User';
                    $email = $application->email ?? 'No email';

                    $initials = '??';
                    if (!empty($name) && $name !== 'Unknown User') {
                      $clean = preg_replace('/\s+/', ' ', trim($name));
                      $initials = strtoupper(substr($clean, 0, 2));
                    }

                    $badge = $badgeForStatus($application->status ?? null);

                    $type = $application->type ?? 'Pengajuan';
                    $icon = 'fa-file-alt';
                    if (strtolower($type) === 'beasiswa') $icon = 'fa-graduation-cap';
                    if (strtolower($type) === 'kerja sama') $icon = 'fa-handshake';
                    if (strtolower($type) === 'prestasi') $icon = 'fa-trophy';
                  @endphp

                  <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td class="px-4 py-3">
                      <div class="d-flex align-items-center">
                        <div class="bg-assa-blue rounded-circle d-flex align-items-center justify-content-center text-white fw-medium text-sm"
                             style="width: 40px; height: 40px;">
                          {{ $initials }}
                        </div>
                        <div class="ms-3">
                          <div class="text-sm fw-bold">{{ $name }}</div>
                          <div class="text-sm text-muted">{{ $email }}</div>
                        </div>
                      </div>
                    </td>

                    <td class="px-4 py-3">
                      <div class="d-flex align-items-center">
                        <i class="fas {{ $icon }} assa-blue me-2"></i>
                        <span class="text-sm fw-medium">{{ $type }}</span>
                      </div>
                    </td>

                    <td class="px-4 py-3 text-sm text-muted">
                      {{ !empty($application->created_at) ? \Carbon\Carbon::parse($application->created_at)->format('d M Y') : '-' }}
                    </td>

                    <td class="px-4 py-3">
                      <span class="badge-rounded" style="background-color: {{ $badge['bg'] }}; color: {{ $badge['fg'] }};">
                        {{ $badge['text'] }}
                      </span>
                    </td>

                    <td class="px-4 py-3 text-sm fw-medium">
                      <a href="{{ $currentUrl }}" class="assa-blue text-decoration-none fw-medium me-3">Review</a>
                      <a href="{{ $currentUrl }}" class="text-muted text-decoration-none">Detail</a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="5" class="px-4 py-4 text-center text-muted">
                    Belum ada pengajuan terbaru.
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-4 py-3 border-top" style="background-color: #f9fafb;">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div class="text-sm text-muted">
              @if($pageFirst && $pageLast && $pageTotal !== null)
                Menampilkan <span class="fw-medium">{{ $pageFirst }}-{{ $pageLast }}</span> dari
                <span class="fw-medium">{{ $pageTotal }}</span> pengajuan
              @else
                Menampilkan data pengajuan
              @endif
            </div>

            <div>
              @if(isset($recent_applications) && method_exists($recent_applications, 'links'))
                {{ $recent_applications->links('pagination::bootstrap-5') }}
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
