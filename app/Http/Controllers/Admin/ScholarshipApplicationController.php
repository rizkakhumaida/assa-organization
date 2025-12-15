<?php

namespace App\Http\Controllers\admin;

use App\Models\ScholarshipApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ScholarshipApplicationController extends Controller
{
    /**
     * 🧾 Tampilkan daftar semua aplikasi beasiswa
     */
    public function index()
    {
        // Ambil semua data aplikasi beasiswa dengan relasi user (nama pendaftar)
        $applications = ScholarshipApplication::with(['user:id,name'])
            ->select([
                'id',
                'user_id',
                'nama_pendaftar',
                'campus',
                'program_studi',
                'dokumen',
                'status',
            ])
            ->orderByDesc('id')
            ->paginate(9);

        return view('admin.scholarship.index', compact('applications'));
    }

    /**
     * 📄 Tampilkan detail satu aplikasi beasiswa
     */
    public function show($id)
    {
        $scholarship = ScholarshipApplication::with(['user:id,name'])->findOrFail($id);

        return view('admin.scholarship.show', compact('scholarship'));
    }

    /**
     * 🗑️ Hapus data aplikasi beasiswa
     */
    public function destroy($id)
    {
        $scholarship = ScholarshipApplication::findOrFail($id);

        // Hapus file dokumen jika ada
        if ($scholarship->dokumen) {
            Storage::disk('public')->delete($scholarship->dokumen);
        }

        $scholarship->delete();

        return redirect()
            ->route('admin.scholarship.index')
            ->with('success', 'Data beasiswa berhasil dihapus!');
    }
}
