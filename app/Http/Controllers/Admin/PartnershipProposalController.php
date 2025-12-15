<?php

namespace App\Http\Controllers\Admin;

use App\Models\PartnershipProposal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response; // ✅ Import Response facade
use Barryvdh\DomPDF\Facade\Pdf; // ✅ Import PDF facade yang benar
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PartnershipExport;

class PartnershipProposalController extends Controller
{
    // =============================
    // 📋 Tampilkan daftar proposal (ADMIN: semua data)
    // =============================
    public function index(Request $request)
    {
        $proposals = PartnershipProposal::latest()->paginate(10)->withQueryString();

        return view('admin.partnerships.index', compact('proposals'));
    }

    // =============================
    // 📝 Form pengajuan proposal
    // =============================
    public function create()
    {
        // Jenis kerja sama bisa diatur dari sini (dropdown di form create)
        $jenisKerjasamaList = [
            'Pendidikan',
            'Penelitian',
            'Pengabdian Masyarakat',
            'Magang / Internship',
            'CSR / Sosial',
            'Lainnya',
        ];

        return view('admin.partnerships.create', compact('jenisKerjasamaList'));
    }

    // =============================
    // 💾 Simpan proposal baru
    // =============================
    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_name' => 'required|string|max:150',
            'contact_email'     => 'required|email',
            'contact_phone'     => 'nullable|string|max:30',
            'contact_person'    => 'nullable|string|max:100',
            'position'          => 'nullable|string|max:100',
            'cooperation_type'  => 'required|string|max:100',
            'jenis_kerjasama'   => 'required|string|max:100', // ✅ field baru
            'proposal_summary'  => 'required|string|max:3000',
            'document'          => 'nullable|file|mimes:pdf|max:4096',
        ]);

        $path = $request->file('document')?->store('partnerships', 'public');

        $proposal = PartnershipProposal::create([
            'user_id'           => $request->user()->id,
            'organization_name' => $data['organization_name'],
            'contact_email'     => $data['contact_email'],
            'contact_phone'     => $data['contact_phone'] ?? null,
            'contact_person'    => $data['contact_person'] ?? null,
            'position'          => $data['position'] ?? null,
            'cooperation_type'  => $data['cooperation_type'],
            'jenis_kerjasama'   => $data['jenis_kerjasama'], // ✅ simpan ke DB
            'proposal_summary'  => $data['proposal_summary'],
            'document_path'     => $path,
            'status'            => 'submitted',
        ]);

        return redirect()
            ->route('admin.partnerships.show', $proposal)
            ->with('success', 'Pengajuan kerja sama berhasil dikirim.');
    }

    // =============================
    // 🔍 Tampilkan detail proposal
    // =============================
    public function show(PartnershipProposal $partnership)
    {
        return view('admin.partnerships.show', ['proposal' => $partnership]);
    }

    // =============================
    // ✏️ Form edit (untuk admin update status / catatan)
    // =============================
    public function edit(PartnershipProposal $partnership)
    {
        return view('admin.partnerships.edit', ['proposal' => $partnership]);
    }

    // =============================
    // 💾 Update data proposal
    // =============================
    public function update(Request $request, PartnershipProposal $partnership)
    {
        $data = $request->validate([
            'status' => 'required|string|in:submitted,review,pending,approved,rejected,onhold',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $partnership->update($data);

        return redirect()
            ->route('admin.partnerships.index')
            ->with('success', 'Status pengajuan kerja sama berhasil diperbarui.');
    }

    // =============================
    // 📥 Download dokumen proposal
    // =============================
    public function download($id)
    {
        $proposal = PartnershipProposal::findOrFail($id);

        if (!$proposal->document_path || !Storage::disk('public')->exists($proposal->document_path)) {
            return redirect()->back()->with('error', 'File proposal tidak ditemukan.');
        }

        // ✅ Perbaikan: Gunakan Response::download() dengan path lengkap
        $filePath = storage_path('app/public/' . $proposal->document_path);
        $fileName = $proposal->organization_name . '_proposal_' . $proposal->id . '.pdf';

        return Response::download($filePath, $fileName);
    }

    // =============================
    // 🧾 Export PDF (ADMIN: semua data)
    // =============================
    public function exportPDF(Request $request)
    {
        $proposals = PartnershipProposal::orderByDesc('created_at')->get();

        // ✅ Perbaikan: Gunakan Pdf facade yang benar
        $pdf = Pdf::loadView('admin.partnerships.pdf', compact('proposals'));

        return $pdf->download('partnership_proposals_' . date('Y-m-d') . '.pdf');
    }

    // =============================
    // 📊 Export Excel (ADMIN: semua data)
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
