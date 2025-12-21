@extends('layouts.app')

@section('content')
<style>
  /* ===== ASSA Premium UI ===== */
  :root{
    --assa-primary:#1E3A8A;
    --assa-secondary:#3B82F6;
    --assa-soft:#EEF2FF;
    --assa-muted:#6B7280;
  }

  body{
    background: linear-gradient(135deg,#EEF2FF 0%,#F8FAFC 100%);
  }

  /* Utilities */
  .assa-blue{color:var(--assa-primary)}
  .bg-assa{background:linear-gradient(135deg,var(--assa-primary),var(--assa-secondary))}
  .rounded-2xl{border-radius:1.25rem}
  .rounded-xl{border-radius:0.9rem}
  .glass{
    background:rgba(255,255,255,.85);
    backdrop-filter:blur(14px);
  }
  .hover-lift{
    transition:.35s ease;
  }
  .hover-lift:hover{
    transform:translateY(-4px);
    box-shadow:0 18px 40px rgba(0,0,0,.12);
  }

  /* Buttons */
  .btn-assa{
    background:linear-gradient(135deg,var(--assa-primary),var(--assa-secondary));
    color:#fff;
    border:none;
    border-radius:.75rem;
    padding:.55rem 1.1rem;
    font-weight:600;
  }
  .btn-assa:hover{color:#fff;box-shadow:0 10px 25px rgba(30,58,138,.45)}

  /* Badges */
  .badge-soft{
    border-radius:999px;
    padding:.45rem .9rem;
    font-weight:600;
    font-size:.8rem;
  }

  /* Hero */
  .hero{
    position:relative;
    overflow:hidden;
  }
  .hero::after{
    content:"";
    position:absolute;
    inset:0;
    background:radial-gradient(circle at top right,rgba(255,255,255,.25),transparent 60%);
  }

  /* Avatar */
  .avatar{
    width:44px;height:44px;
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-weight:700;color:#fff;
    background:linear-gradient(135deg,var(--assa-primary),var(--assa-secondary));
  }
</style>

<div class="container-fluid py-3">

  <!-- Back Button -->
  <div class="mb-3">
    <a href="{{ url()->previous() }}" class="btn btn-light rounded-xl shadow-sm">
      <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
  </div>

  <!-- HERO HEADER -->
  <div class="hero bg-assa text-white rounded-2xl shadow-lg mb-4">
    <div class="p-4 position-relative">
      <h2 class="fw-bold mb-2">{{ $activity->title }}</h2>
      <div class="d-flex flex-wrap gap-3 small opacity-90">
        <span><i class="fas fa-map-marker-alt me-1"></i>{{ $activity->location ?? '-' }}</span>
        <span><i class="fas fa-calendar-alt me-1"></i>
          {{ optional($activity->start_at)->format('d M Y H:i') ?? '-' }}
          @if($activity->end_at) – {{ optional($activity->end_at)->format('d M Y H:i') }} @endif
        </span>
      </div>

      @php $isSoon = $activity->start_at && $activity->start_at->isFuture(); @endphp
      <div class="mt-3">
        @if($isSoon)
          <span class="badge-soft" style="background:#DBEAFE;color:#1E40AF">🚀 Akan Berlangsung</span>
        @else
          <span class="badge-soft" style="background:#DCFCE7;color:#166534">✅ Selesai / Berjalan</span>
        @endif
      </div>
    </div>
  </div>

  <!-- CONTENT CARD -->
  <div class="glass rounded-2xl shadow-lg p-4 mb-4 hover-lift">
    <h5 class="fw-bold assa-blue mb-3"><i class="fas fa-info-circle me-2"></i>Deskripsi Kegiatan</h5>
    <p class="text-muted" style="line-height:1.8">{{ $activity->description ?: 'Tidak ada deskripsi.' }}</p>
  </div>

  <!-- PARTICIPANTS -->
  @if(isset($activity->participants) && $activity->participants->count())
    <div class="glass rounded-2xl shadow-lg p-4 mb-4">
      <h5 class="fw-bold assa-blue mb-4"><i class="fas fa-users me-2"></i>Peserta ({{ $activity->participants->count() }})</h5>
      <div class="row g-3">
        @foreach($activity->participants->take(12) as $participant)
          <div class="col-md-6 col-xl-4">
            <div class="d-flex align-items-center gap-3 p-3 rounded-xl bg-white shadow-sm hover-lift">
              <div class="avatar">{{ strtoupper(substr($participant->name,0,2)) }}</div>
              <div class="flex-fill">
                <div class="fw-semibold">{{ $participant->name }}</div>
                <div class="text-muted small">{{ $participant->email }}</div>
                <span class="badge-soft mt-1 d-inline-block" style="background:#EEF2FF;color:#1E3A8A">
                  {{ ucfirst($participant->pivot->status ?? 'registered') }}
                </span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      @if($activity->participants->count() > 12)
        <div class="text-center text-muted small mt-3">
          +{{ $activity->participants->count() - 12 }} peserta lainnya
        </div>
      @endif
    </div>
  @endif

  <!-- RELATED ACTIVITIES -->
  @if(($related ?? collect())->count())
    <div class="glass rounded-2xl shadow-lg p-4">
      <h5 class="fw-bold assa-blue mb-4"><i class="fas fa-calendar-alt me-2"></i>Kegiatan Lainnya</h5>
      <div class="row g-3">
        @foreach($related as $act)
          <div class="col-md-6 col-xl-4">
            <div class="h-100 p-3 rounded-xl bg-white shadow-sm hover-lift">
              <h6 class="fw-bold mb-1">
                <a href="{{ route('admin.activities.show',$act->id) }}" class="assa-blue text-decoration-none">
                  {{ $act->title }}
                </a>
              </h6>
              <div class="text-muted small mb-2">
                <div><i class="fas fa-map-marker-alt me-1"></i>{{ $act->location ?? '-' }}</div>
                <div><i class="fas fa-clock me-1"></i>{{ optional($act->start_at)->format('d M Y H:i') }}</div>
              </div>
              <p class="text-muted small">{{ \Illuminate\Support\Str::limit($act->description,90) }}</p>
              <div class="text-end">
                <a href="{{ route('admin.activities.show',$act->id) }}" class="btn btn-assa btn-sm">
                  Detail <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

</div>
@endsection
