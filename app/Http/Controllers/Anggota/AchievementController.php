<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::where('user_id', Auth::id())
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->get();

        return view('anggota.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('anggota.achievements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullName'   => 'required|string|max:255',
            'school'     => 'required|string|max:255',
            'nim'        => 'nullable|string|max:50',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',

            'title'      => 'required|string|max:255',
            'tingkat'    => 'required|string|max:100',
            'kategori'   => 'required|string|max:100',
            'date'       => 'required|date',
            'description'=> 'required|string',

            // OPSI A:
            // certificate = sertifikat/piagam (WAJIB)
            // photo       = foto prestasi (OPSIONAL)
            'certificate'=> 'required|mimes:pdf,jpg,jpeg,png|max:10240',
            'photo'      => 'nullable|mimes:jpg,jpeg,png|max:10240',
        ]);

        // =========================
        // HITUNG POIN DARI TINGKAT
        // =========================
        $tingkatPoint = [
            'internasional'     => 50,
            'nasional'          => 40,
            'provinsi'          => 30,
            'kabupaten/kota'    => 20,
            'institusi/kampus'  => 10,
        ];

        $tingkatKey = strtolower(trim($request->tingkat));
        $poin = $tingkatPoint[$tingkatKey] ?? 0;

        // Upload sertifikat (WAJIB)
        $sertifikatPath = $request->file('certificate')
            ->store('prestasi/sertifikat', 'public');

        // Upload foto (OPSIONAL)
        $fotoPath = null;
        if ($request->hasFile('photo')) {
            $fotoPath = $request->file('photo')
                ->store('prestasi/foto', 'public');
        }

        $prestasi = Achievement::create([
            'user_id'      => Auth::id(),
            'peserta'      => $request->fullName,
            'asal_sekolah' => $request->school,
            'nim'          => $request->nim,
            'email'        => $request->email,
            'phone'        => $request->phone,

            'prestasi'  => $request->title,
            'tingkat'   => $request->tingkat,
            'kategori'  => $request->kategori,
            'tahun'     => $request->date,
            'deskripsi' => $request->description,

            'poin'       => $poin,                // ✅ otomatis, tidak kosong
            'sertifikat' => $sertifikatPath,      // ✅ sertifikat/piagam
            'certificate'=> $fotoPath,            // ✅ foto (boleh NULL)

            'status' => 'Pending',
            'aksi'   => 'Approve / Reject',        // ✅ SESUAI PERMINTAAN
        ]);

        return redirect()
            ->route('anggota.achievements.show', $prestasi->id)
            ->with('success', 'Prestasi berhasil disetor!');
    }

    public function show($id)
    {
        $data = Achievement::where('user_id', Auth::id())->findOrFail($id);
        return view('anggota.achievements.show', compact('data'));
    }

    public function preview($id)
    {
        $achievement = Achievement::where('user_id', Auth::id())->findOrFail($id);

        if (!Storage::disk('public')->exists($achievement->sertifikat)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file(
            Storage::disk('public')->path($achievement->sertifikat)
        );
    }

    public function download($id)
    {
        $achievement = Achievement::where('user_id', Auth::id())->findOrFail($id);

        if (!Storage::disk('public')->exists($achievement->sertifikat)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $achievement->sertifikat,
            'Bukti_Prestasi_'.$achievement->peserta.'.'.pathinfo($achievement->sertifikat, PATHINFO_EXTENSION)
        );
    }
}
