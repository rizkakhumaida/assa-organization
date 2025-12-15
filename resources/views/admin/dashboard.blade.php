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

  .rounded-2xl { border-radius: 1rem; }
  .rounded-xl { border-radius: 0.75rem; }
  .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
  .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2); }
  .bg-opacity-10 { background-color: rgba(255, 255, 255, 0.1); }

  .stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
  }

  .notification-card {
    padding: 1.25rem;
    border-radius: 0.75rem;
    border-left: 4px solid;
  }

  .notification-yellow {
    background: linear-gradient(to right, #fffbeb, #fef3c7);
    border-left-color: #f59e0b;
  }

  .notification-blue {
    background: linear-gradient(to right, #eff6ff, #dbeafe);
    border-left-color: #1E3A8A;
  }

  .notification-green {
    background: linear-gradient(to right, #f0fdf4, #dcfce7);
    border-left-color: #10b981;
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
  }
</style>

<div class="container-fluid p-0">
  {{-- Admin Welcome --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h2 class="fw-bold mb-2 fs-2">Admin Dashboard 👨‍💼</h2>
            <p class="mb-0 fs-5" style="color: #bfdbfe;">Kelola dan pantau aktivitas ASSA Organization</p>
          </div>
          <div class="d-none d-md-block">
            <div class="bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 96px; height: 96px; background-color: rgba(255, 255, 255, 0.2);">
              <i class="fas fa-chart-line" style="font-size: 2.5rem;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Admin Stats --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
      <div class="stat-card card-hover h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Beasiswa Diajukan</p>
            <p class="fw-bold mb-1 assa-blue" style="font-size: 2rem;">
              {{ isset($stats) && is_array($stats) && isset($stats['scholarships_pending']) ? $stats['scholarships_pending'] : 48 }}
            </p>
            <p class="text-sm text-success">↑ 15% dari bulan lalu</p>
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
            <p class="text-muted text-uppercase fw-semibold mb-1 text-sm tracking-wide">Jumlah Kerjasama</p>
            <p class="fw-bold mb-1 text-success" style="font-size: 2rem;">
              {{ isset($stats) && is_array($stats) && isset($stats['partnerships_total']) ? $stats['partnerships_total'] : 27 }}
            </p>
            <p class="text-sm text-success">↑ 8% dari bulan lalu</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(16, 185, 129, 0.1);">
            <i class="fas fa-handshake text-success" style="font-size: 1.5rem;"></i>
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
              {{ isset($stats) && is_array($stats) && isset($stats['achievements_total']) ? $stats['achievements_total'] : 184 }}
            </p>
            <p class="text-sm text-success">↑ 22% dari bulan lalu</p>
          </div>
          <div class="d-flex align-items-center justify-content-center rounded-2xl"
               style="width: 64px; height: 64px; background-color: rgba(139, 92, 246, 0.1);">
            <i class="fas fa-trophy" style="font-size: 1.5rem; color: #8b5cf6;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    {{-- Chart --}}
    <div class="col-xl-6">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <h3 class="fw-bold mb-4 fs-5">📊 Trend Pengajuan Bulanan</h3>
        <div class="chart-container">
          <div class="chart-bar">
            <div class="chart-bar-fill" style="height: 120px;"></div>
            <span class="text-xs text-muted mt-3 fw-medium">Jan</span>
            <span class="text-xs text-muted">32</span>
          </div>
          <div class="chart-bar">
            <div class="chart-bar-fill" style="height: 180px;"></div>
            <span class="text-xs text-muted mt-3 fw-medium">Feb</span>
            <span class="text-xs text-muted">45</span>
          </div>
          <div class="chart-bar">
            <div class="chart-bar-fill" style="height: 150px;"></div>
            <span class="text-xs text-muted mt-3 fw-medium">Mar</span>
            <span class="text-xs text-muted">38</span>
          </div>
          <div class="chart-bar">
            <div class="chart-bar-fill" style="height: 220px;"></div>
            <span class="text-xs text-muted mt-3 fw-medium">Apr</span>
            <span class="text-xs text-muted">52</span>
          </div>
          <div class="chart-bar">
            <div class="chart-bar-fill" style="height: 160px;"></div>
            <span class="text-xs text-muted mt-3 fw-medium">Mei</span>
            <span class="text-xs text-muted">41</span>
          </div>
          <div class="chart-bar">
            <div class="chart-bar-fill" style="height: 240px;"></div>
            <span class="text-xs text-muted mt-3 fw-medium">Jun</span>
            <span class="text-xs text-muted">58</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Admin Notifications --}}
    <div class="col-xl-6">
      <div class="bg-white rounded-2xl shadow-lg p-4 border card-hover h-100">
        <h3 class="fw-bold mb-4 fs-5">🔔 Notifikasi Admin</h3>
        <div class="vstack gap-3">
          <div class="notification-card notification-yellow">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-fill">
                <h4 class="fw-bold mb-1">⏰ Review Diperlukan</h4>
                <p class="text-sm text-muted mb-2">
                  {{ isset($notifications) && is_array($notifications) && isset($notifications['pending_reviews']) ? $notifications['pending_reviews'] : 8 }}
                  pengajuan beasiswa menunggu persetujuan Anda
                </p>
                <span class="badge-rounded text-white" style="background-color: #f59e0b;">Urgent</span>
              </div>
            </div>
          </div>

          <div class="notification-card notification-blue">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-fill">
                <h4 class="fw-bold mb-1">📋 Laporan Bulanan</h4>
                <p class="text-sm text-muted mb-2">Laporan aktivitas Desember 2024 siap untuk dikirim</p>
                <span class="badge-rounded bg-assa-blue text-white">Info</span>
              </div>
            </div>
          </div>

          <div class="notification-card notification-green">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-fill">
                <h4 class="fw-bold mb-1">✅ Event Selesai</h4>
                <p class="text-sm text-muted mb-2">
                  Futsal ASSA x UHB berhasil dilaksanakan dengan
                  {{ isset($events) && is_array($events) && isset($events['futsal_participants']) ? $events['futsal_participants'] : 24 }}
                  peserta
                </p>
                <span class="badge-rounded bg-success text-white">Sukses</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Applications Table --}}
  <div class="row">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <div class="p-4 border-bottom" style="background-color: #f9fafb;">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-bold mb-0 fs-5">📋 Pengajuan Terbaru</h3>
            <div class="d-flex gap-2">
              <button class="btn bg-assa-blue text-white btn-sm fw-medium" style="border-radius: 0.5rem;">
                Export Data
              </button>
              <button class="btn btn-outline-secondary btn-sm fw-medium" style="border-radius: 0.5rem;">
                Filter
              </button>
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
              @if(isset($recent_applications) && count($recent_applications) > 0)
                @foreach($recent_applications as $application)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <div class="bg-assa-blue rounded-circle d-flex align-items-center justify-content-center text-white fw-medium text-sm"
                           style="width: 40px; height: 40px;">
                        {{ $application->user ? substr($application->user->name, 0, 2) : '??' }}
                      </div>
                      <div class="ms-3">
                        <div class="text-sm fw-bold">{{ $application->user->name ?? 'Unknown User' }}</div>
                        <div class="text-sm text-muted">{{ $application->user->email ?? 'No email' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-graduation-cap assa-blue me-2"></i>
                      <span class="text-sm fw-medium">{{ $application->type ?? 'Beasiswa Prestasi' }}</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm text-muted">
                    {{ $application->created_at ? $application->created_at->format('d M Y') : 'No date' }}
                  </td>
                  <td class="px-4 py-3">
                    @if(isset($application->status))
                      @if($application->status === 'pending')
                        <span class="badge-rounded" style="background-color: #fef3c7; color: #92400e;">Pending Review</span>
                      @elseif($application->status === 'approved')
                        <span class="badge-rounded" style="background-color: #dcfce7; color: #166534;">Approved</span>
                      @else
                        <span class="badge-rounded" style="background-color: #fee2e2; color: #991b1b;">Rejected</span>
                      @endif
                    @else
                      <span class="badge-rounded" style="background-color: #fef3c7; color: #92400e;">Unknown</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-sm fw-medium">
                    <a href="#" class="assa-blue text-decoration-none fw-medium me-3">Review</a>
                    <a href="#" class="text-muted text-decoration-none">Detail</a>
                  </td>
                </tr>
                @endforeach
              @else
                {{-- Fallback hardcoded data jika tidak ada data dari backend --}}
                <tr style="border-bottom: 1px solid #e5e7eb;">
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <div class="bg-assa-blue rounded-circle d-flex align-items-center justify-content-center text-white fw-medium text-sm"
                           style="width: 40px; height: 40px;">
                        RK
                      </div>
                      <div class="ms-3">
                        <div class="text-sm fw-bold">Rizka Khumaida</div>
                        <div class="text-sm text-muted">rizka.khumaida@email.com</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-graduation-cap assa-blue me-2"></i>
                      <span class="text-sm fw-medium">Beasiswa Prestasi</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm text-muted">12 Jan 2024</td>
                  <td class="px-4 py-3">
                    <span class="badge-rounded" style="background-color: #fef3c7; color: #92400e;">Pending Review</span>
                  </td>
                  <td class="px-4 py-3 text-sm fw-medium">
                    <a href="#" class="assa-blue text-decoration-none fw-medium me-3">Review</a>
                    <a href="#" class="text-muted text-decoration-none">Detail</a>
                  </td>
                </tr>

                <tr style="border-bottom: 1px solid #e5e7eb;">
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white fw-medium text-sm"
                           style="width: 40px; height: 40px;">
                        SN
                      </div>
                      <div class="ms-3">
                        <div class="text-sm fw-bold">Siti Nurhaliza</div>
                        <div class="text-sm text-muted">siti.nur@email.com</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-handshake text-success me-2"></i>
                      <span class="text-sm fw-medium">Kerjasama Penelitian</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm text-muted">10 Jan 2024</td>
                  <td class="px-4 py-3">
                    <span class="badge-rounded" style="background-color: #dcfce7; color: #166534;">Approved</span>
                  </td>
                  <td class="px-4 py-3 text-sm fw-medium">
                    <a href="#" class="text-success text-decoration-none fw-medium me-3">View</a>
                    <a href="#" class="text-muted text-decoration-none">Detail</a>
                  </td>
                </tr>

                <tr style="border-bottom: 1px solid #e5e7eb;">
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-medium text-sm"
                           style="width: 40px; height: 40px; background-color: #8b5cf6;">
                        BS
                      </div>
                      <div class="ms-3">
                        <div class="text-sm fw-bold">Budi Santoso</div>
                        <div class="text-sm text-muted">budi.santoso@email.com</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-trophy me-2" style="color: #8b5cf6;"></i>
                      <span class="text-sm fw-medium">Prestasi Lomba</span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm text-muted">08 Jan 2024</td>
                  <td class="px-4 py-3">
                    <span class="badge-rounded" style="background-color: #fee2e2; color: #991b1b;">Rejected</span>
                  </td>
                  <td class="px-4 py-3 text-sm fw-medium">
                    <a href="#" class="text-danger text-decoration-none fw-medium me-3">Review</a>
                    <a href="#" class="text-muted text-decoration-none">Detail</a>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="px-4 py-3 border-top" style="background-color: #f9fafb;">
          <div class="d-flex justify-content-between align-items-center">
            <div class="text-sm text-muted">
              Menampilkan <span class="fw-medium">1-3</span> dari
              <span class="fw-medium">{{ isset($total_applications) ? $total_applications : 48 }}</span> pengajuan
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-outline-secondary btn-sm">Previous</button>
              <button class="btn bg-assa-blue text-white btn-sm">Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
