<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AchievementExport;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AchievementController extends Controller
{
    // Opsi tingkat yang ditampilkan di form (UI)
    private const LEVELS_UI = [
        'Internasional',
        'Nasional',
        'Provinsi',
        'Kabupaten/Kota',
        'Institusi/Kampus',
    ];

    // Opsi status yang ditampilkan di form (UI)
    private const STATUSES_UI = [
        'Pending',
        'Disetujui',
        'Ditolak',
        'Ditunda',
    ];

    // Nilai status yang AMAN disimpan ke database (DB lama Anda)
    // Biasanya kolom status hanya mengizinkan ini (enum / varchar pendek)
    private const STATUSES_DB = [
        'Pending',
        'Verified',
        'Rejected',
    ];

    public function index()
    {
        $achievements = Achievement::latest()->paginate(10);
        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievements.create');
    }

    private function normalizeLevel(?string $tingkat): string
    {
        $t = strtolower(trim((string) $tingkat));

        if (in_array($t, ['internasional', 'international'], true)) return 'Internasional';
        if ($t === 'nasional') return 'Nasional';
        if ($t === 'provinsi') return 'Provinsi';

        if (in_array($t, ['kabupaten', 'kota', 'kabupaten/kota', 'kabupaten-kota'], true)) {
            return 'Kabupaten/Kota';
        }

        if (in_array($t, ['institusi', 'kampus', 'institusi/kampus', 'institusi-kampus'], true)) {
            return 'Institusi/Kampus';
        }

        foreach (self::LEVELS_UI as $opt) {
            if (strcasecmp($tingkat ?? '', $opt) === 0) return $opt;
        }

        return 'Institusi/Kampus';
    }

    private function pointByLevel(?string $tingkat): int
    {
        return match ($this->normalizeLevel($tingkat)) {
            'Internasional'    => 50,
            'Nasional'         => 40,
            'Provinsi'         => 30,
            'Kabupaten/Kota'   => 20,
            'Institusi/Kampus' => 10,
            default            => 0,
        };
    }

    /**
     * Terima status dari UI (Pending/Disetujui/Ditolak/Ditunda atau legacy)
     * lalu SIMPAN nilai aman untuk DB (Pending/Verified/Rejected)
     */
    private function statusUiToDb(?string $status): string
    {
        $s = strtolower(trim((string) $status));

        // UI baru (Indonesia)
        if ($s === 'pending')   return 'Pending';
        if ($s === 'disetujui') return 'Verified';
        if ($s === 'ditolak')   return 'Rejected';
        if ($s === 'ditunda')   return 'Pending'; // DB lama biasanya tidak punya "Ditunda"

        // Legacy (jika data lama / input lama)
        if (in_array($s, ['verified', 'approved', 'accepted'], true)) return 'Verified';
        if (in_array($s, ['rejected', 'declined'], true)) return 'Rejected';

        // Jika sudah tepat salah satu DB value
        foreach (self::STATUSES_DB as $opt) {
            if (strcasecmp($status ?? '', $opt) === 0) return $opt;
        }

        return 'Pending';
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'peserta'      => 'required|string|max:255',
            'prestasi'     => 'required|string|max:255',

            'tingkat'      => 'required|string|in:' . implode(',', self::LEVELS_UI),
            'kategori'     => 'nullable|string|max:100',
            'tahun'        => 'nullable|date',

            'asal_sekolah' => 'nullable|string|max:255',
            'nim'          => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'deskripsi'    => 'nullable|string',

            'sertifikat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'certificate'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            // validasi sesuai UI (Indonesia)
            'status'       => 'required|string|in:' . implode(',', self::STATUSES_UI),
        ]);

        $data['tingkat'] = $this->normalizeLevel($data['tingkat']);
        $data['poin']    = $this->pointByLevel($data['tingkat']);

        // SIMPAN status aman untuk DB
        $data['status']  = $this->statusUiToDb($data['status']);

        if ($request->hasFile('sertifikat')) {
            $data['sertifikat'] = $request->file('sertifikat')->store('prestasi/sertifikat', 'public');
        }

        if ($request->hasFile('certificate')) {
            $data['certificate'] = $request->file('certificate')->store('prestasi/foto', 'public');
        }

        $data['aksi'] = $data['aksi'] ?? 'Detail / Update';

        Achievement::create($data);

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Prestasi berhasil ditambahkan.');
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
            'peserta'      => 'required|string|max:255',
            'prestasi'     => 'required|string|max:255',

            'tingkat'      => 'required|string|in:' . implode(',', self::LEVELS_UI),
            'kategori'     => 'nullable|string|max:100',
            'tahun'        => 'nullable|date',

            'asal_sekolah' => 'nullable|string|max:255',
            'nim'          => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'deskripsi'    => 'nullable|string',

            'sertifikat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'certificate'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',

            // validasi sesuai UI (Indonesia)
            'status'       => 'required|string|in:' . implode(',', self::STATUSES_UI),
        ]);

        $data['tingkat'] = $this->normalizeLevel($data['tingkat']);
        $data['poin']    = $this->pointByLevel($data['tingkat']);

        // SIMPAN status aman untuk DB
        $data['status']  = $this->statusUiToDb($data['status']);

        if ($request->hasFile('sertifikat')) {
            if ($achievement->sertifikat && Storage::disk('public')->exists($achievement->sertifikat)) {
                Storage::disk('public')->delete($achievement->sertifikat);
            }
            $data['sertifikat'] = $request->file('sertifikat')->store('prestasi/sertifikat', 'public');
        }

        if ($request->hasFile('certificate')) {
            if (!empty($achievement->certificate) && Storage::disk('public')->exists($achievement->certificate)) {
                Storage::disk('public')->delete($achievement->certificate);
            }
            $data['certificate'] = $request->file('certificate')->store('prestasi/foto', 'public');
        }

        $achievement->update($data);

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->sertifikat && Storage::disk('public')->exists($achievement->sertifikat)) {
            Storage::disk('public')->delete($achievement->sertifikat);
        }
        if (!empty($achievement->certificate) && Storage::disk('public')->exists($achievement->certificate)) {
            Storage::disk('public')->delete($achievement->certificate);
        }

        $achievement->delete();

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Prestasi berhasil dihapus.');
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

        if (!$achievement->sertifikat || !Storage::disk('public')->exists($achievement->sertifikat)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        $ext = pathinfo($achievement->sertifikat, PATHINFO_EXTENSION) ?: 'pdf';
        $safePeserta = preg_replace('/[^A-Za-z0-9_\-]/', '_', $achievement->peserta);
        $safePrestasi = preg_replace('/[^A-Za-z0-9_\-]/', '_', $achievement->prestasi);
        $fileName = $safePeserta . '_' . $safePrestasi . '_' . $achievement->id . '.' . $ext;

        return Storage::disk('public')->download($achievement->sertifikat, $fileName);
    }
}
