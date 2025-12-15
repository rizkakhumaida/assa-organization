@extends('layouts.app')

@section('content')
<style>
  /* ASSA Design System */
  .assa-blue { color: #1E3A8A; }
  .bg-assa-blue { background-color: #1E3A8A; }

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

  .badge-rounded {
    border-radius: 0.75rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
  }

  .btn-assa {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
  }

  .btn-assa:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.4);
    color: white;
  }

  .text-xs { font-size: 0.75rem; }
  .text-sm { font-size: 0.875rem; }
</style>

<div class="container-fluid p-0">
  {{-- Navigation --}}
  <div class="row mb-4">
    <div class="col-12">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
      </a>
    </div>
  </div>

  {{-- Activity Detail Hero --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg border card-hover">
        <div class="p-4">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div class="flex-fill">
              <h1 class="h3 fw-bold assa-blue mb-3">{{ $activity->title }}</h1>
              <div class="row g-3 mb-3">
                <div class="col-auto">
                  <div class="d-flex align-items-center text-muted text-sm">
                    <i class="fas fa-map-marker-alt me-2 assa-blue"></i>
                    <span>{{ $activity->location ?? '-' }}</span>
                  </div>
                </div>
                <div class="col-auto">
                  <div class="d-flex align-items-center text-muted text-sm">
                    <i class="fas fa-calendar me-2 assa-blue"></i>
                    <span>
                      {{ optional($activity->start_at)->format('d M Y H:i') ?? '-' }}
                      @if($activity->end_at)
                        – {{ optional($activity->end_at)->format('d M Y H:i') }}
                      @endif
                    </span>
                  </div>
                </div>
              </div>
            </div>

            @php
              $isSoon = $activity->start_at && $activity->start_at->isFuture();
            @endphp
            <div class="text-center">
              @if($isSoon)
                <span class="badge-rounded" style="background-color: #dbeafe; color: #1e40af;">
                  🚀 Akan berlangsung
                </span>
              @else
                <span class="badge-rounded" style="background-color: #f3f4f6; color: #6b7280;">
                  ✅ Selesai/berjalan
                </span>
              @endif
            </div>
          </div>

          <div class="border-top pt-4">
            <h5 class="fw-bold assa-blue mb-3">
              <i class="fas fa-info-circle me-2"></i>Deskripsi Kegiatan
            </h5>
            <p class="text-muted mb-0" style="line-height: 1.6;">
              {{ $activity->description ?: 'Tidak ada deskripsi.' }}
            </p>
          </div>

          @if(isset($activity->participants) && $activity->participants->count() > 0)
            <div class="border-top pt-4 mt-4">
              <h5 class="fw-bold assa-blue mb-3">
                <i class="fas fa-users me-2"></i>Peserta Terdaftar ({{ $activity->participants->count() }})
              </h5>
              <div class="row g-3">
                @foreach($activity->participants->take(10) as $participant)
                  <div class="col-md-6 col-lg-4">
                    <div class="d-flex align-items-center p-3 rounded-xl" style="background-color: #f8fafc;">
                      <div class="d-flex align-items-center justify-content-center rounded-circle me-3"
                           style="width: 40px; height: 40px; background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); color: white; font-weight: bold;">
                        {{ strtoupper(substr($participant->name, 0, 2)) }}
                      </div>
                      <div>
                        <h6 class="fw-bold mb-0 text-sm">{{ $participant->name }}</h6>
                        <p class="text-muted text-xs mb-0">{{ $participant->email }}</p>
                        <span class="badge-rounded mt-1" style="background-color: #dcfce7; color: #166534; font-size: 0.7rem;">
                          {{ ucfirst($participant->pivot->status ?? 'registered') }}
                        </span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              @if($activity->participants->count() > 10)
                <div class="text-center mt-3">
                  <small class="text-muted">Dan {{ $activity->participants->count() - 10 }} peserta lainnya...</small>
                </div>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Related Activities --}}
  @if(($related ?? collect())->count())
    <div class="row">
      <div class="col-12">
        <div class="bg-white rounded-2xl shadow-lg border card-hover">
          <div class="p-4">
            <h5 class="fw-bold assa-blue mb-4">
              <i class="fas fa-calendar-alt me-2"></i>Kegiatan Lainnya
            </h5>
            <div class="row g-3">
              @foreach($related as $act)
                <div class="col-md-6 col-xl-4">
                  <div class="card h-100 border-0 rounded-xl card-hover" style="background-color: #f8fafc;">
                    <div class="card-body p-3">
                      <h6 class="card-title mb-2 fw-bold">
                        <a href="{{ route('admin.activities.show', $act->id) }}" class="text-decoration-none assa-blue">
                          {{ $act->title }}
                        </a>
                      </h6>
                      <div class="mb-2">
                        <p class="text-muted text-xs mb-1">
                          <i class="fas fa-map-marker-alt me-1"></i>{{ $act->location ?? '-' }}
                        </p>
                        <p class="text-muted text-xs mb-0">
                          <i class="fas fa-calendar me-1"></i>{{ optional($act->start_at)->format('d M Y H:i') ?? '-' }}
                        </p>
                      </div>
                      <p class="mb-0 text-sm text-muted">
                        {{ \Illuminate\Support\Str::limit($act->description, 90) }}
                      </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 text-end">
                      <a href="{{ route('admin.activities.show', $act->id) }}" class="btn btn-assa btn-sm">
                        Detail <i class="fas fa-arrow-right ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
