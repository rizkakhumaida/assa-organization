<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartnershipProposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartnershipProposalController extends Controller
{
    /**
     * Halaman form pengajuan kerjasama.
     */
    public function index()
    {
        return view('anggota.partnerships.index');
    }

    /**
     * Simpan pengajuan kerjasama baru.
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirimkan dari form
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'email'            => 'required|email',
            'address'          => 'required|string',  // Pastikan ini ada dan benar
            'phone'            => 'required|string|max:50',
            'contact_person'   => 'required|string|max:255',
            'position'         => 'required|string|max:255',
            'cooperation_type' => 'required|string',
            'description'      => 'required|string|min:100',
            'proposal_file'    => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Pastikan file proposal ada sebelum melanjutkan
        if ($request->hasFile('proposal_file')) {
            try {
                // Upload file ke storage
                $path = $request->file('proposal_file')->store('partnership_proposals', 'public');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah file proposal: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'File proposal harus diunggah.');
        }

        // Simpan data ke database
        PartnershipProposal::create([
            'user_id'           => Auth::id(),
            'organization_name' => $validated['institution_name'],
            'contact_email'     => $validated['email'],
            'address'           => $validated['address'],  // Pastikan address diterima dengan benar
            'contact_phone'     => $validated['phone'],
            'contact_person'    => $validated['contact_person'],
            'position'          => $validated['position'],
            'cooperation_type'  => $validated['cooperation_type'],
            'proposal_summary'  => $validated['description'],
            'document_path'     => $path,
            'status'            => 'submitted',
            'notes'             => null,
        ]);

        // Redirect setelah menyimpan data dengan pesan sukses
        return redirect()
            ->route('anggota.partnerships.index')  // Atau Anda bisa redirect ke halaman lain seperti daftar proposal
            ->with('success', 'Pengajuan kerjasama berhasil dikirim!');
    }

    /**
     * Tampilkan pengajuan milik anggota.
     */
    public function myProposals()
    {
        $proposals = PartnershipProposal::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('anggota.partnerships.my_proposals', compact('proposals'));
    }

    /**
     * Download file proposal.
     */
    public function downloadFile(PartnershipProposal $proposal)
    {
        if (!Storage::disk('public')->exists($proposal->document_path)) {
            return redirect()->back()->with('error', 'File proposal tidak ditemukan.');
        }

        return Storage::disk('public')->download($proposal->document_path);
    }
}
