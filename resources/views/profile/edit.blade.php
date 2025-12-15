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

  .profile-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    font-weight: bold;
    margin: 0 auto 1.5rem;
  }

  .form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }

  .form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: #1E3A8A;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
  }

  .btn-assa {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
  }

  .btn-assa:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.4);
    color: white;
  }

  .alert-success-custom {
    background: linear-gradient(to right, #f0fdf4, #dcfce7);
    border-left: 4px solid #10b981;
    border-radius: 0.75rem;
    padding: 1rem;
    color: #166534;
  }

  .alert-danger-custom {
    background: linear-gradient(to right, #fef2f2, #fee2e2);
    border-left: 4px solid #ef4444;
    border-radius: 0.75rem;
    padding: 1rem;
    color: #991b1b;
  }

  .section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
  }

  .section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
  }

  .danger-zone {
    background: linear-gradient(to right, #fef2f2, #fee2e2);
    border: 2px solid #fecaca;
    border-radius: 1rem;
    padding: 1.5rem;
  }

  .text-xs { font-size: 0.75rem; }
  .text-sm { font-size: 0.875rem; }
</style>

<div class="container-fluid p-0">
  {{-- Header Section --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
          <div class="text-center text-md-start mb-3 mb-md-0">
            <h2 class="fw-bold mb-2 fs-2">⚙️ Pengaturan Profil</h2>
            <p class="mb-0 fs-5" style="color: #bfdbfe;">Kelola informasi profil dan keamanan akun Anda</p>
          </div>
          <div class="profile-avatar" style="margin: 0;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- Update Profile Information --}}
    <div class="col-xl-6">
      <div class="bg-white rounded-2xl shadow-lg border card-hover h-100">
        <div class="p-4">
          <div class="section-title">
            <div class="section-icon">
              <i class="fas fa-user"></i>
            </div>
            <div>
              <h3 class="fw-bold mb-0 fs-5">Informasi Profil</h3>
              <p class="text-muted text-sm mb-0">Update informasi dasar akun Anda</p>
            </div>
          </div>

          <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
              <label for="name" class="form-label">
                <i class="fas fa-user me-2 assa-blue"></i>Nama Lengkap
              </label>
              <input
                id="name"
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
                autocomplete="name"
                placeholder="Masukkan nama lengkap Anda"
              >
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">
                <i class="fas fa-envelope me-2 assa-blue"></i>Email
              </label>
              <input
                id="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="email"
                placeholder="Masukkan email Anda"
              >
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label">
                <i class="fas fa-shield-alt me-2 assa-blue"></i>Role
              </label>
              <div class="d-flex align-items-center gap-2">
                <span class="badge" style="background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem;">
                  {{ ucfirst($user->role) }}
                </span>
                <span class="text-muted text-sm">Role tidak dapat diubah</span>
              </div>
            </div>

            <button type="submit" class="btn btn-assa w-100">
              <i class="fas fa-save me-2"></i>
              Simpan Perubahan Profil
            </button>
          </form>

          @if (session('status') === 'profile-updated')
            <div class="alert-success-custom mt-3">
              <i class="fas fa-check-circle me-2"></i>
              <strong>Berhasil!</strong> Profil Anda telah diperbarui.
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Update Password --}}
    <div class="col-xl-6">
      <div class="bg-white rounded-2xl shadow-lg border card-hover h-100">
        <div class="p-4">
          <div class="section-title">
            <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
              <i class="fas fa-lock"></i>
            </div>
            <div>
              <h3 class="fw-bold mb-0 fs-5">Keamanan Password</h3>
              <p class="text-muted text-sm mb-0">Ubah password untuk menjaga keamanan akun</p>
            </div>
          </div>

          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
              <label for="current_password" class="form-label">
                <i class="fas fa-key me-2" style="color: #f59e0b;"></i>Password Saat Ini
              </label>
              <input
                id="current_password"
                type="password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                name="current_password"
                autocomplete="current-password"
                placeholder="Masukkan password saat ini"
              >
              @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">
                <i class="fas fa-key me-2" style="color: #f59e0b;"></i>Password Baru
              </label>
              <input
                id="password"
                type="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                name="password"
                autocomplete="new-password"
                placeholder="Masukkan password baru"
              >
              @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="password_confirmation" class="form-label">
                <i class="fas fa-check-double me-2" style="color: #f59e0b;"></i>Konfirmasi Password Baru
              </label>
              <input
                id="password_confirmation"
                type="password"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                name="password_confirmation"
                autocomplete="new-password"
                placeholder="Ulangi password baru"
              >
              @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; font-weight: 600; border-radius: 0.5rem; padding: 0.75rem 1.5rem;">
              <i class="fas fa-shield-alt me-2"></i>
              Update Password
            </button>
          </form>

          @if (session('status') === 'password-updated')
            <div class="alert-success-custom mt-3">
              <i class="fas fa-check-circle me-2"></i>
              <strong>Berhasil!</strong> Password Anda telah diperbarui.
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Account Statistics (New Feature) --}}
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg border card-hover">
        <div class="p-4">
          <div class="section-title">
            <div class="section-icon" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
              <i class="fas fa-chart-bar"></i>
            </div>
            <div>
              <h3 class="fw-bold mb-0 fs-5">Statistik Akun</h3>
              <p class="text-muted text-sm mb-0">Ringkasan aktivitas Anda di ASSA Organization</p>
            </div>
          </div>

          {{-- Update bagian Account Statistics dengan safe fallback --}}
          <div class="row g-3">
            <div class="col-md-3">
              <div class="text-center p-3 rounded-xl" style="background: linear-gradient(to right, #eff6ff, #dbeafe);">
                <i class="fas fa-calendar-check assa-blue mb-2" style="font-size: 2rem;"></i>
                <h4 class="fw-bold assa-blue mb-1">
                  @try
                    {{ auth()->user()->activities()->count() }}
                  @catch
                    0
                  @endtry
                </h4>
                <p class="text-sm text-muted mb-0">Kegiatan Diikuti</p>
              </div>
            </div>

            <div class="col-md-3">
              <div class="text-center p-3 rounded-xl" style="background: linear-gradient(to right, #f0fdf4, #dcfce7);">
                <i class="fas fa-graduation-cap text-success mb-2" style="font-size: 2rem;"></i>
                <h4 class="fw-bold text-success mb-1">
                  @if(Schema::hasTable('scholarship_applications'))
                    @try
                      {{ auth()->user()->scholarships()->count() }}
                    @catch
                      0
                    @endtry
                  @else
                    0
                  @endif
                </h4>
                <p class="text-sm text-muted mb-0">Aplikasi Beasiswa</p>
              </div>
            </div>

            <div class="col-md-3">
              <div class="text-center p-3 rounded-xl" style="background: linear-gradient(to right, #fefbeb, #fef3c7);">
                <i class="fas fa-handshake" style="color: #f59e0b; font-size: 2rem;" class="mb-2"></i>
                <h4 class="fw-bold mb-1" style="color: #f59e0b;">
                  @if(Schema::hasTable('partnership_proposals'))
                    @try
                      {{ auth()->user()->partnerships()->count() }}
                    @catch
                      0
                    @endtry
                  @else
                    0
                  @endif
                </h4>
                <p class="text-sm text-muted mb-0">Proposal Kerjasama</p>
              </div>
            </div>

            <div class="col-md-3">
              <div class="text-center p-3 rounded-xl" style="background: linear-gradient(to right, #faf5ff, #f3e8ff);">
                <i class="fas fa-trophy mb-2" style="color: #8b5cf6; font-size: 2rem;"></i>
                <h4 class="fw-bold mb-1" style="color: #8b5cf6;">
                  @if(Schema::hasTable('achievements'))
                    @try
                      {{ auth()->user()->achievements()->count() }}
                    @catch
                      0
                    @endtry
                  @else
                    0
                  @endif
                </h4>
                <p class="text-sm text-muted mb-0">Prestasi Dicatat</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Delete Account --}}
    <div class="col-12">
      <div class="bg-white rounded-2xl shadow-lg border card-hover">
        <div class="p-4">
          <div class="section-title">
            <div class="section-icon" style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
              <h3 class="fw-bold mb-0 fs-5 text-danger">Zona Berbahaya</h3>
              <p class="text-muted text-sm mb-0">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan</p>
            </div>
          </div>

          <div class="danger-zone">
            <div class="d-flex align-items-center mb-3">
              <i class="fas fa-trash-alt text-danger me-3" style="font-size: 1.5rem;"></i>
              <div>
                <h5 class="fw-bold text-danger mb-1">Hapus Akun Permanen</h5>
                <p class="text-muted text-sm mb-0">
                  Setelah akun dihapus, semua data, riwayat aktivitas, dan informasi pribadi akan hilang selamanya.
                </p>
              </div>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}" class="d-flex flex-column flex-md-row gap-3 align-items-end">
              @csrf
              @method('delete')

              <div class="flex-fill">
                <label for="delete_password" class="form-label text-danger fw-medium">
                  Konfirmasi dengan Password
                </label>
                <input
                  id="delete_password"
                  type="password"
                  class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                  name="password"
                  placeholder="Masukkan password untuk konfirmasi"
                  autocomplete="current-password"
                >
                @error('password', 'userDeletion')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <button
                type="submit"
                class="btn btn-danger"
                style="border-radius: 0.5rem; padding: 0.75rem 1.5rem; font-weight: 600;"
                onclick="return confirm('⚠️ PERINGATAN: Apakah Anda benar-benar yakin ingin menghapus akun? Semua data akan hilang permanen dan tidak dapat dikembalikan!')"
              >
                <i class="fas fa-trash-alt me-2"></i>
                Hapus Akun
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
