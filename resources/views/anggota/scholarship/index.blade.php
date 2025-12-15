@extends('layouts.app')

@section('title', 'Pengajuan Beasiswa')

@section('content')
<div class="container py-4">

    {{-- ============================= --}}
    {{-- HEADER BEASISWA --}}
    {{-- ============================= --}}
    <div class="p-4 mb-4 rounded-4"
        style="background: linear-gradient(90deg, #1E3A8A 0%, #3B82F6 100%); color: white;">

        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="bg-white bg-opacity-25 p-3 rounded-3 d-flex justify-content-center align-items-center"
                    style="width: 56px; height: 56px;">
                    <i class="fas fa-graduation-cap fa-lg text-white"></i>
                </div>
            </div>

            <div>
                <h3 class="fw-bold mb-1">Program Beasiswa ASSA</h3>
                <p class="mb-0" style="opacity: .9;">
                    Ajukan permohonan beasiswa untuk jenjang pendidikan dengan mudah & cepat.
                </p>
            </div>
        </div>

    </div>


    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert error --}}
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


    {{-- ============================= --}}
    {{-- FORM PENGAJUAN BEASISWA --}}
    {{-- ============================= --}}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Form Pengajuan Beasiswa</h4>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('anggota.scholarship.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- DATA DIRI --}}
                <h5 class="fw-bold text-primary mb-3">Data Diri</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                        class="form-control @error('full_name') is-invalid @enderror" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Asal Kampus</label>
                    <input type="text" name="campus" value="{{ old('campus') }}"
                        class="form-control @error('campus') is-invalid @enderror" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Alasan Pengajuan Beasiswa (Minimal 150 kata)</label>
                    <textarea name="reason" rows="5"
                        class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                </div>


                {{-- DOKUMEN --}}
                <h5 class="fw-bold text-primary mt-4 mb-3">Upload Dokumen</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">KTP</label>
                        <input type="file" name="file_ktp" class="form-control" required>
                        <small class="text-muted">Format: PDF/JPG, Maks. 5MB</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kartu Keluarga (KK)</label>
                        <input type="file" name="file_kk" class="form-control" required>
                        <small class="text-muted">Format: PDF/JPG, Maks. 5MB</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ijazah + Transkrip Nilai</label>
                        <input type="file" name="file_ijazah_transkrip" class="form-control" required>
                        <small class="text-muted">Format: PDF, Maks. 10MB</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">KIP / PKH (Jika Ada)</label>
                        <input type="file" name="file_kip_pkh" class="form-control" required>
                        <small class="text-muted">Format: PDF/JPG, Maks. 5MB</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Surat Keterangan Tidak Mampu (SKTM)</label>
                        <input type="file" name="file_sktm" class="form-control" required>
                        <small class="text-muted">Format: PDF/JPG, Maks. 5MB</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Sertifikat Prestasi</label>
                        <input type="file" name="file_prestasi" class="form-control" required>
                        <small class="text-muted">Format: PDF/JPG, Maks. 10MB</small>
                    </div>
                </div>


                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        Kirim Pengajuan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
