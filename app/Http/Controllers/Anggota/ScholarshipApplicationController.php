<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarshipApplication;

class ScholarshipApplicationController extends Controller
{
    /**
     * Tampilkan halaman form beasiswa
     */
    public function index()
    {
        return view('anggota.scholarship.index');
    }

    /**
     * Proses penyimpanan pengajuan beasiswa
     */
    public function store(Request $request)
    {
        // Validasi form
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email',
            'campus'    => 'required|string|max:255',
            'reason'    => 'required|string|min:150',

            // Upload files
            'file_ktp'              => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk'               => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ijazah_transkrip' => 'required|mimes:pdf|max:10240',
            'file_kip_pkh'          => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sktm'             => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_prestasi'         => 'required|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $folder = 'scholarship_documents';

        // Upload file
        $fileKtp           = $request->file('file_ktp')->store($folder, 'public');
        $fileKk            = $request->file('file_kk')->store($folder, 'public');
        $fileIjazah        = $request->file('file_ijazah_transkrip')->store($folder, 'public');
        $fileKipPkh        = $request->file('file_kip_pkh')->store($folder, 'public');
        $fileSktm          = $request->file('file_sktm')->store($folder, 'public');
        $filePrestasi      = $request->file('file_prestasi')->store($folder, 'public');

        // Simpan ke database
        ScholarshipApplication::create([
            'user_id'  => Auth::id(),
            'full_name'=> $validated['full_name'],
            'email'    => $validated['email'],
            'campus'   => $validated['campus'],
            'reason'   => $validated['reason'],

            'file_ktp'              => $fileKtp,
            'file_kk'               => $fileKk,
            'file_ijazah_transkrip' => $fileIjazah,
            'file_kip_pkh'          => $fileKipPkh,
            'file_sktm'             => $fileSktm,
            'file_prestasi'         => $filePrestasi,

            'status' => 'pending',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pengajuan beasiswa berhasil dikirim! Kami akan menghubungi Anda setelah proses verifikasi.');
    }
}
