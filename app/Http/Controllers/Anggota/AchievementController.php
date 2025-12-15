<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    /**
     * Daftar prestasi milik anggota yang login
     */
    public function index()
    {
        $achievements = Achievement::where('user_id', Auth::id())
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->get();

        return view('anggota.achievements.index', compact('achievements'));
    }

    /**
     * Proses penyimpanan formulir setor prestasi
     */
    public function store(Request $request)
    {
        // VALIDASI: sesuaikan dengan name di form Blade
        $request->validate([
            'fullName'    => 'required|string|max:255',
            'school'      => 'required|string|max:255',
            'nim'         => 'nullable|string|max:50',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:20',

            'title'       => 'required|string|max:255',
            'level'       => 'required|string|max:100',
            'category'    => 'required|string|max:100',
            'date'        => 'required|date',
            'description' => 'required|string',

            'certificate' => 'required|mimes:pdf,jpg,jpeg,png|max:10240', // sertifikat/piagam
            'photo'       => 'nullable|mimes:jpg,jpeg,png|max:10240',      // foto opsional
        ]);

        // ---------------- Upload Sertifikat (wajib) ----------------
        $sertifikatPath = $request->file('certificate')
            ->store('prestasi/sertifikat', 'public');

        // ---------------- Upload Foto (opsional) ----------------
        $fotoPath = null;
        if ($request->hasFile('photo')) {
            $fotoPath = $request->file('photo')
                ->store('prestasi/foto', 'public');
        }

        // ---------------- Simpan ke Database ----------------
        $prestasi = Achievement::create([
            'user_id'      => Auth::id(),

            // biodata peserta
            'peserta'      => $request->fullName,
            'asal_sekolah' => $request->school,
            'nim'          => $request->nim,
            'email'        => $request->email,
            'phone'        => $request->phone,

            // data prestasi
            'prestasi'     => $request->title,
            'tingkat'      => $request->level,
            'kategori'     => $request->category,
            'tahun'        => $request->date,          // kolom bertipe date
            'deskripsi'    => $request->description,

            // file & status
            'poin'         => 0,
            'sertifikat'   => $sertifikatPath,
            'certificate'  => $fotoPath,
            'status'       => 'Pending',               // sesuai enum di migration
            'aksi'         => '-',
        ]);

        return redirect()
            ->route('anggota.achievements.show', $prestasi->id)
            ->with('success', 'Prestasi berhasil disetor!');
    }

    /**
     * Detail satu prestasi milik anggota yang login
     */
    public function show($id)
    {
        $data = Achievement::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('anggota.achievements.show', compact('data'));
    }
}
