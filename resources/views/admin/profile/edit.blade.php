@extends('layouts.app')

@section('content')
<style>
/* 🌈 STYLE KHUSUS HALAMAN EDIT PROFIL */
body {
    background: linear-gradient(180deg, #f6f9ff 0%, #eef2ff 100%);
    font-family: 'Poppins', sans-serif;
}

.profile-card {
    background: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: 0.3s ease;
}

.profile-card:hover {
    transform: translateY(-3px);
}

.card-header-modern {
    background: linear-gradient(90deg, #1e88e5, #42a5f5);
    color: #fff;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 1.2rem;
}

.form-label {
    font-weight: 600;
    color: #1a237e;
}

.form-control, textarea {
    border-radius: 12px !important;
    border: 1.5px solid #e0e0e0;
    transition: all 0.3s ease;
}

.form-control:focus, textarea:focus {
    border-color: #42a5f5;
    box-shadow: 0 0 0 0.15rem rgba(33,150,243,0.25);
}

.btn-save {
    background: linear-gradient(45deg, #1e88e5, #42a5f5);
    border: none;
    color: #fff;
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33,150,243,0.3);
}

.btn-cancel {
    border-radius: 12px;
    font-weight: 500;
}

.alert {
    border-radius: 12px;
}
</style>

<div class="container py-4">
    <div class="profile-card animate__animated animate__fadeIn">
        <div class="card-header-modern mb-4">
            <i class="bi bi-person-circle me-2"></i> Edit Profil
        </div>

        {{-- Notifikasi Berhasil --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Notifikasi Error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Ups! Ada yang salah:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            <h6 class="fw-bold text-primary mb-3">
                <i class="bi bi-lock-fill me-1"></i> Ubah Password (Opsional)
            </h6>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-save me-2">
                    <i class="bi bi-save2 me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-cancel">
                    <i class="bi bi-arrow-left-circle me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
