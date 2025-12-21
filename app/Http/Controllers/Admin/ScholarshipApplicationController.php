<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipApplicationController extends Controller
{
    /**
     * 🧾 Daftar semua pengajuan beasiswa
     */
    public function index()
    {
        $applications = ScholarshipApplication::with('user:id,name')
            ->select([
                'id',
                'user_id',
                'full_name',
                'email',
                'campus',
                'status',
                'created_at',
            ])
            ->latest()
            ->paginate(9);

        return view('admin.scholarship.index', compact('applications'));
    }

    /**
     * 📄 Detail satu pengajuan
     */
    public function show($id)
    {
        $scholarship = ScholarshipApplication::with('user:id,name')->findOrFail($id);

        return view('admin.scholarship.show', compact('scholarship'));
    }

    /**
     * ✏️ Form edit pengajuan
     */
    public function edit($id)
    {
        $scholarship = ScholarshipApplication::with('user:id,name')->findOrFail($id);

        return view('admin.scholarship.edit', compact('scholarship'));
    }

    /**
     * ✅ Update pengajuan (data utama)
     */
    public function update(Request $request, $id)
    {
        $scholarship = ScholarshipApplication::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'campus'    => 'nullable|string|max:255',
            'status'    => 'required|in:pending,approved,rejected,on hold',
            'reason'    => 'nullable|string',
        ]);

        $scholarship->update($validated);

        return redirect()
            ->route('admin.scholarship.show', $scholarship->id)
            ->with('success', 'Data pengajuan berhasil diperbarui!');
    }

    /**
     * ⬇️ Download dokumen pendukung (AMAN & FORCE DOWNLOAD)
     */
    public function download($id, $type)
    {
        $scholarship = ScholarshipApplication::findOrFail($id);

        $files = [
            'ktp'       => $scholarship->file_ktp,
            'kk'        => $scholarship->file_kk,
            'ijazah'    => $scholarship->file_ijazah_transkrip,
            'kip'       => $scholarship->file_kip_pkh,
            'sktm'      => $scholarship->file_sktm,
            'prestasi'  => $scholarship->file_prestasi,
        ];

        if (!isset($files[$type]) || !$files[$type]) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        $path = $files[$type];

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak tersedia di server');
        }

        // FORCE DOWNLOAD (bukan preview)
        return Storage::disk('public')->download($path);
    }

    /**
     * 🗑️ Hapus pengajuan + semua file
     */
    public function destroy($id)
    {
        $scholarship = ScholarshipApplication::findOrFail($id);

        $files = [
            'file_ktp',
            'file_kk',
            'file_ijazah_transkrip',
            'file_kip_pkh',
            'file_sktm',
            'file_prestasi',
        ];

        foreach ($files as $file) {
            if ($scholarship->$file && Storage::disk('public')->exists($scholarship->$file)) {
                Storage::disk('public')->delete($scholarship->$file);
            }
        }

        $scholarship->delete();

        return redirect()
            ->route('admin.scholarship.index')
            ->with('success', 'Data beasiswa berhasil dihapus!');
    }
}
