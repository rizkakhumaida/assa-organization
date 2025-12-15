<?php

namespace App\Http\Controllers\admin;

use App\Models\Achievement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AchievementExport;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::latest()->paginate(10);
        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'peserta'   => 'required|string|max:255',
            'prestasi'  => 'required|string|max:255',
            'tingkat'   => 'required|string|max:100',
            'kategori'  => 'required|string|max:100',
            'tahun'     => 'required|date',
            'poin'      => 'nullable|integer',
            'sertifikat'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'status'    => 'required|string|in:Pending,Verified,Rejected',
        ]);

        // ✅ Jika ingin poin otomatis berdasarkan tingkat
        // $data['poin'] = match ($data['tingkat']) {
        //     'Internasional' => 10,
        //     'Nasional' => 8,
        //     'Provinsi' => 5,
        //     'Kabupaten' => 3,
        //     default => 1,
        // };

        $path = $request->file('sertifikat')
            ? $request->file('sertifikat')->store('sertifikats', 'public')
            : null;

        Achievement::create([
            ...$data,
            'sertifikat' => $path,
            'aksi'      => 'Detail / Update',
        ]);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $achievement = Achievement::findOrFail($id);
        return view('admin.achievements.show', compact('achievement'));
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $data = $request->validate([
            'peserta'   => 'required|string|max:255',
            'prestasi'  => 'required|string|max:255',
            'tingkat'   => 'required|string|max:100',
            'kategori'  => 'required|string|max:100',
            'tahun'     => 'required|date',
            'poin'      => 'nullable|integer',
            'sertifikat'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'status'    => 'required|string|in:Pending,Verified,Rejected',
        ]);

        if ($request->hasFile('sertifikat')) {
            if ($achievement->sertifikat && Storage::disk('public')->exists($achievement->sertifikat)) {
                Storage::disk('public')->delete($achievement->sertifikat);
            }
            $data['sertifikat'] = $request->file('sertifikat')->store('sertifikats', 'public');
        }

        $achievement->update($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->sertifikat && Storage::disk('public')->exists($achievement->sertifikat)) {
            Storage::disk('public')->delete($achievement->sertifikat);
        }

        $achievement->delete();

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil dihapus.');
    }

    public function exportPDF()
    {
        $achievements = Achievement::orderByDesc('tahun')->get();
        $pdf = PDF::loadView('admin.achievements.pdf', compact('achievements'));
        return $pdf->download('daftar_prestasi.pdf');
    }

    public function exportExcel()
    {
        $achievements = Achievement::orderByDesc('tahun')->get();
        return Excel::download(new AchievementExport($achievements), 'daftar_prestasi.xlsx');
    }

    public function download($id)
    {
        $achievement = Achievement::findOrFail($id);

        if (! $achievement->sertifikat || ! Storage::disk('public')->exists($achievement->sertifikat)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return Storage::disk('public')->download($achievement->sertifikat);
    }
}
