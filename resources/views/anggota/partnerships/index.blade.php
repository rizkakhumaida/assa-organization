@extends('layouts.app')

@section('title', 'Pengajuan Kerjasama')

@section('content')
<div class="container py-4">

    {{-- ========================= --}}
    {{-- HEADER PAGE --}}
    {{-- ========================= --}}
    <div class="p-4 mb-4 rounded-4"
        style="background: linear-gradient(90deg, #1E3A8A 0%, #3B82F6 100%); color: white;">

        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="bg-white bg-opacity-25 p-3 rounded-3 d-flex justify-content-center align-items-center"
                    style="width: 56px; height: 56px;">
                    <i class="fas fa-handshake fa-lg text-white"></i>
                </div>
            </div>

            <div>
                <h3 class="fw-bold mb-1">Pengajuan Kerjasama</h3>
                <p class="mb-0" style="opacity: .9;">
                    Ajukan proposal kerjasama dengan ASSA Organization dengan mudah dan cepat
                </p>
            </div>
        </div>
    </div>


    {{-- SUCCESS & ERROR MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ========================= --}}
    {{-- FORM KERJASAMA --}}
    {{-- ========================= --}}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Formulir Pengajuan Kerjasama</h4>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('anggota.partnerships.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- IDENTITAS INSTANSI --}}
                <h5 class="fw-bold text-primary mb-3">Identitas Instansi</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Instansi *</label>
                        <input type="text" name="institution_name" class="form-control" value="{{ old('institution_name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email Resmi *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat *</label>
                    <textarea name="address" rows="2" class="form-control" required>{{ old('address') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                </div>


                {{-- PIC / Penanggung Jawab --}}
                <h5 class="fw-bold text-primary mt-4 mb-3">Penanggung Jawab</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Penanggung Jawab *</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jabatan *</label>
                        <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                    </div>
                </div>


                {{-- JENIS KERJASAMA --}}
                <h5 class="fw-bold text-primary mt-4 mb-3">Jenis Kerjasama</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Jenis Kerjasama *</label>
                    <select name="cooperation_type" class="form-control" required>
                        <option value="">-- Pilih Jenis Kerjasama --</option>
                        <option value="Beasiswa">Beasiswa</option>
                        <option value="Sponsorship">Sponsorship</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Seminar">Seminar</option>
                        <option value="Event">Event</option>
                        <option value="Penelitian">Penelitian</option>
                        <option value="Magang">Magang</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>


                {{-- DESKRIPSI --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Deskripsi Kerjasama *</label>
                    <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                </div>


                {{-- FILE PROPOSAL --}}
                <h5 class="fw-bold text-primary mt-4 mb-3">Proposal / Dokumen Kerjasama</h5>

                <div class="mb-3">
                    <input type="file" name="proposal_file" class="form-control" required>
                    <small class="text-muted">Format diperbolehkan: PDF / DOC / DOCX (maks. 10MB)</small>
                </div>


                {{-- SUBMIT BUTTON --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        Ajukan Kerjasama
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
