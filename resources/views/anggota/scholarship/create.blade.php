@extends('layouts.app')

@section('title', 'Pengajuan Beasiswa - ASSA')

@section('content')
<div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8">

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Form Pengajuan Beasiswa ASSA</h1>
            <p class="text-gray-600 mt-2">Lengkapi seluruh data berikut untuk mengajukan beasiswa.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-md mb-6">
                <strong>Ada kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('anggota.scholarship.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block font-semibold text-gray-700">Nama Lengkap *</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                       class="w-full border rounded-lg px-4 py-3 mt-2 focus:ring-blue-700 focus:border-blue-700"
                       required>
            </div>

            {{-- Email --}}
            <div>
                <label class="block font-semibold text-gray-700">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded-lg px-4 py-3 mt-2 focus:ring-blue-700 focus:border-blue-700"
                       required>
            </div>

            {{-- Kampus --}}
            <div>
                <label class="block font-semibold text-gray-700">Pilih Kampus *</label>
                <select name="campus" class="w-full border rounded-lg px-4 py-3 mt-2" required>
                    <option value="">-- Pilih Kampus --</option>
                    <optgroup label="Purwokerto">
                        <option value="UNSOED">UNSOED</option>
                        <option value="UMP">UMP</option>
                        <option value="ITTP">ITTP</option>
                    </optgroup>
                    <optgroup label="Yogyakarta">
                        <option value="UGM">UGM</option>
                        <option value="UNY">UNY</option>
                        <option value="UII">UII</option>
                    </optgroup>
                </select>
            </div>

            {{-- Alasan --}}
            <div>
                <label class="block font-semibold text-gray-700">Alasan Mengajukan Beasiswa *</label>
                <textarea name="reason" class="w-full border rounded-lg px-4 py-3 mt-2" rows="5"
                          placeholder="Minimal 150 karakter" required>{{ old('reason') }}</textarea>
            </div>

            {{-- Dokumen --}}
            <h3 class="text-lg font-bold text-gray-800 mt-6">Upload Dokumen Persyaratan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block font-semibold text-gray-700">KTP *</label>
                    <input type="file" name="file_ktp" required accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full border rounded-lg px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700">Kartu Keluarga *</label>
                    <input type="file" name="file_kk" required accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full border rounded-lg px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700">Ijazah & Transkrip *</label>
                    <input type="file" name="file_ijazah_transkrip" required accept=".pdf"
                           class="w-full border rounded-lg px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700">KIP / PKH *</label>
                    <input type="file" name="file_kip_pkh" required accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full border rounded-lg px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700">SKTM *</label>
                    <input type="file" name="file_sktm" required accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full border rounded-lg px-4 py-3 mt-2">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700">Sertifikat Prestasi *</label>
                    <input type="file" name="file_prestasi" required accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full border rounded-lg px-4 py-3 mt-2">
                </div>

            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-4 rounded-lg">
                Kirim Pengajuan Beasiswa
            </button>
        </form>

    </div>
</div>
@endsection
