@extends('layouts.app')

@section('title', 'Tambah Pengajuan Kerjasama')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-1">
                Tambah Pengajuan Kerjasama
            </h1>
            <p class="text-slate-500 text-sm">
                Lengkapi formulir di bawah ini untuk mengajukan kerjasama ke ASSA Organization.
            </p>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card Form --}}
        <div class="bg-white shadow-xl rounded-2xl p-8">
            <form method="POST"
                  action="{{ route('anggota.partnerships.store') }}" {{-- SUDAH REVISI --}}
                  enctype="multipart/form-data"
                  class="space-y-8">
                @csrf

                {{-- Instansi & Email --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="institution_name" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Instansi / Perusahaan *
                        </label>
                        <input type="text"
                               id="institution_name"
                               name="institution_name"
                               value="{{ old('institution_name') }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                               placeholder="PT. Contoh Perusahaan / Universitas ABC">
                        @error('institution_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                            Email Resmi *
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                               placeholder="partnership@perusahaan.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat & Telepon --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">
                            Alamat Lengkap *
                        </label>
                        <textarea id="address"
                                  name="address"
                                  rows="4"
                                  required
                                  class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none"
                                  placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nomor Telepon *
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                               placeholder="+62 21 1234 5678">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Penanggung Jawab --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_person" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Penanggung Jawab *
                        </label>
                        <input type="text"
                               id="contact_person"
                               name="contact_person"
                               value="{{ old('contact_person') }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                               placeholder="Nama lengkap penanggung jawab">
                        @error('contact_person')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position" class="block text-sm font-semibold text-slate-700 mb-2">
                            Jabatan Penanggung Jawab *
                        </label>
                        <input type="text"
                               id="position"
                               name="position"
                               value="{{ old('position') }}"
                               required
                               class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                               placeholder="Direktur, Manager, Koordinator, dll.">
                        @error('position')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Jenis Kerjasama --}}
                <div>
                    <label for="cooperation_type" class="block text-sm font-semibold text-slate-700 mb-2">
                        Jenis Kerjasama *
                    </label>
                    <select id="cooperation_type"
                            name="cooperation_type"
                            required
                            class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="">Pilih Jenis Kerjasama</option>
                        <option value="Beasiswa"    {{ old('cooperation_type') == 'Beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                        <option value="Sponsorship" {{ old('cooperation_type') == 'Sponsorship' ? 'selected' : '' }}>Sponsorship</option>
                        <option value="Kegiatan"    {{ old('cooperation_type') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="Seminar"     {{ old('cooperation_type') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="Event"       {{ old('cooperation_type') == 'Event' ? 'selected' : '' }}>Event</option>
                        <option value="Lainnya"     {{ old('cooperation_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('cooperation_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                        Deskripsi Kerjasama *
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="6"
                              required
                              class="block w-full rounded-lg border border-slate-300 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none"
                              placeholder="Jelaskan secara detail mengenai kerjasama yang diajukan">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Proposal --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Upload Proposal / Dokumen *
                    </label>
                    <div class="border-2 border-dashed border-slate-300 rounded-lg px-4 py-6 text-center cursor-pointer hover:border-blue-500 hover:bg-slate-50 transition"
                         onclick="document.getElementById('proposal_file').click()">
                        <p class="text-sm text-slate-700 font-medium">
                            Klik untuk memilih file atau drop file di sini
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            Format: PDF, DOC, DOCX (maksimal 10MB)
                        </p>
                        <p id="fileName" class="text-xs text-slate-400 mt-2"></p>
                    </div>
                    <input type="file"
                           id="proposal_file"
                           name="proposal_file"
                           accept=".pdf,.doc,.docx"
                           class="hidden"
                           required>
                    @error('proposal_file')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Submit --}}
                <div class="flex items-center justify-between">
                    <a href="{{ url()->previous() }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Batal
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-1">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
