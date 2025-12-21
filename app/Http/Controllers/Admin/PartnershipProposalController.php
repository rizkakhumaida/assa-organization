<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PartnershipExport;
use App\Http\Controllers\Controller;
use App\Models\PartnershipProposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PartnershipProposalController extends Controller
{
    private array $jenisKerjasamaList = [
        'Beasiswa',
        'Sponsorship',
        'Kegiatan',
        'Seminar',
        'Event',
        'Penelitian',
        'Magang',
        'Lainnya',
    ];

    // =============================
    // 📋 List (ADMIN)
    // =============================
    public function index(Request $request)
    {
        $proposals = PartnershipProposal::latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.partnerships.index', compact('proposals'));
    }

    // =============================
    // 📝 Form create
    // =============================
    public function create()
    {
        $jenisKerjasamaList = $this->jenisKerjasamaList;

        return view('admin.partnerships.create', compact('jenisKerjasamaList'));
    }

    // =============================
    // 💾 Store
    // =============================
    public function store(Request $request)
    {
        // Catatan:
        // - Kita utamakan "cooperation_type" sebagai field jenis kerjasama.
        // - Kalau form Anda masih memakai "jenis_kerjasama", tetap kita terima dan map ke cooperation_type.
        $data = $request->validate([
            'organization_name' => 'required|string|max:150',
            'contact_email'     => 'required|email|max:255',
            'contact_phone'     => 'nullable|string|max:30',
            'contact_person'    => 'nullable|string|max:100',
            'position'          => 'nullable|string|max:100',

            // boleh salah satu dari dua field (agar kompatibel)
            'cooperation_type'  => 'nullable|string|in:Beasiswa,Sponsorship,Kegiatan,Seminar,Event,Penelitian,Magang,Lainnya',
            'jenis_kerjasama'   => 'nullable|string|in:Beasiswa,Sponsorship,Kegiatan,Seminar,Event,Penelitian,Magang,Lainnya',

            'proposal_summary'  => 'required|string|max:3000',
            'document'          => 'nullable|file|mimes:pdf|max:4096',
        ]);

        // Ambil jenis dari cooperation_type dulu, kalau kosong ambil dari jenis_kerjasama
        $jenis = $data['cooperation_type'] ?? $data['jenis_kerjasama'] ?? null;

        if (!$jenis) {
            return back()
                ->withErrors(['cooperation_type' => 'Jenis kerjasama wajib dipilih.'])
                ->withInput();
        }

        $path = $request->file('document')?->store('partnership_proposals', 'public');

        $proposal = PartnershipProposal::create([
            'user_id'           => $request->user()->id,
            'organization_name' => $data['organization_name'],
            'contact_email'     => $data['contact_email'],
            'contact_phone'     => $data['contact_phone'] ?? null,
            'contact_person'    => $data['contact_person'] ?? null,
            'position'          => $data['position'] ?? null,

            // ✅ sumber kebenaran di DB
            'cooperation_type'  => $jenis,

            // opsional: kalau kolom ini masih ada di DB, silakan simpan juga agar kompatibel
            // kalau kolomnya TIDAK ADA, hapus baris ini
            'jenis_kerjasama'   => $jenis,

            'proposal_summary'  => $data['proposal_summary'],
            'document_path'     => $path,
            'status'            => 'submitted',
        ]);

        return redirect()
            ->route('admin.partnerships.show', $proposal->id)
            ->with('success', 'Pengajuan kerja sama berhasil dikirim.');
    }

    // =============================
    // 🔍 Show
    // =============================
    public function show(PartnershipProposal $partnership)
    {
        return view('admin.partnerships.show', [
            'proposal' => $partnership,
        ]);
    }

    // =============================
    // ✏️ Edit
    // =============================
    public function edit(PartnershipProposal $partnership)
    {
        return view('admin.partnerships.edit', [
            'proposal' => $partnership,
        ]);
    }

    // =============================
    // 💾 Update
    // =============================
    public function update(Request $request, PartnershipProposal $partnership)
    {
        // ✅ Sekarang edit bisa ubah jenis kerja sama juga (cooperation_type)
        $data = $request->validate([
            'cooperation_type' => 'required|string|in:Beasiswa,Sponsorship,Kegiatan,Seminar,Event,Penelitian,Magang,Lainnya',
            'status'           => 'required|string|in:submitted,review,pending,approved,rejected,onhold',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $partnership->update([
            'cooperation_type' => $data['cooperation_type'],
            // opsional: sinkronkan juga jika kolom jenis_kerjasama masih dipakai
            // kalau kolomnya TIDAK ADA, hapus baris ini
            'jenis_kerjasama'  => $data['cooperation_type'],

            'status'           => $data['status'],
            'notes'            => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.partnerships.index')
            ->with('success', 'Status dan jenis kerja sama berhasil diperbarui.');
    }

    // =============================
    // 🗑️ Destroy
    // =============================
    public function destroy(PartnershipProposal $partnership)
    {
        if ($partnership->document_path && Storage::disk('public')->exists($partnership->document_path)) {
            Storage::disk('public')->delete($partnership->document_path);
        }

        $partnership->delete();

        return redirect()
            ->route('admin.partnerships.index')
            ->with('success', 'Proposal kerja sama berhasil dihapus.');
    }

    // =============================
    // 📥 Download dokumen (LEWAT CONTROLLER)
    // =============================
    public function download(PartnershipProposal $partnership)
    {
        if (!$partnership->document_path || !Storage::disk('public')->exists($partnership->document_path)) {
            return redirect()->back()->with('error', 'File proposal tidak ditemukan.');
        }

        $ext = pathinfo($partnership->document_path, PATHINFO_EXTENSION) ?: 'pdf';
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $partnership->organization_name);
        $fileName = $safeName . '_proposal_' . $partnership->id . '.' . $ext;

        return Storage::disk('public')->download($partnership->document_path, $fileName);
    }

    // =============================
    // 🧾 Export PDF
    // =============================
    public function exportPDF(Request $request)
    {
        $proposals = PartnershipProposal::orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('admin.partnerships.pdf', compact('proposals'));

        return $pdf->download('partnership_proposals_' . date('Y-m-d') . '.pdf');
    }

    // =============================
    // 📊 Export Excel
    // =============================
    public function exportExcel(Request $request)
    {
        $proposals = PartnershipProposal::orderByDesc('created_at')->get();

        return Excel::download(
            new PartnershipExport($proposals),
            'partnership_proposals_' . date('Y-m-d') . '.xlsx'
        );
    }
}
