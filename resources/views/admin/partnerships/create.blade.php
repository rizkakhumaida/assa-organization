<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnershipProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartnershipController extends Controller
{
    /**
     * Tampilkan semua data partnership
     */
    public function index()
    {
        $partnerships = PartnershipProposal::latest()->paginate(10);
        return view('admin.partnerships.index', compact('partnerships'));
    }

    /**
     * Form create partnership
     */
    public function create()
    {
        return view('admin.partnerships.create');
    }

    /**
     * Simpan proposal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:150',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:30',
            'contact_person' => 'nullable|string|max:120',
            'position' => 'nullable|string|max:120',
            'jenis_kerjasama' => 'required|string|max:50', // dari form
            'proposal_summary' => 'required|string',
            'document' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Simpan file PDF jika ada
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('partnerships', 'public');
        }

        PartnershipProposal::create([
            'user_id' => Auth::id(),
            'organization_name' => $request->organization_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_person' => $request->contact_person,
            'position' => $request->position,
            'cooperation_type' => $request->jenis_kerjasama, // disamakan ke kolom DB
            'proposal_summary' => $request->proposal_summary,
            'document_path' => $documentPath,
            'status' => 'submitted',
        ]);

        return redirect()->route('admin.partnerships.index')
            ->with('success', 'Proposal kerja sama berhasil diajukan!');
    }

    /**
     * Detail proposal
     */
    public function show(PartnershipProposal $partnership)
    {
        return view('admin.partnerships.show', compact('partnership'));
    }

    /**
     * Edit proposal
     */
    public function edit(PartnershipProposal $partnership)
    {
        return view('admin.partnerships.edit', compact('partnership'));
    }

    /**
     * Update data proposal
     */
    public function update(Request $request, PartnershipProposal $partnership)
    {
        $request->validate([
            'organization_name' => 'required|string|max:150',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:30',
            'contact_person' => 'nullable|string|max:120',
            'position' => 'nullable|string|max:120',
            'jenis_kerjasama' => 'required|string|max:50',
            'proposal_summary' => 'required|string',
            'document' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'in:submitted,review,pending,approved,rejected,onhold',
        ]);

        $documentPath = $partnership->document_path;
        if ($request->hasFile('document')) {
            if ($documentPath) {
                Storage::disk('public')->delete($documentPath);
            }
            $documentPath = $request->file('document')->store('partnerships', 'public');
        }

        $partnership->update([
            'organization_name' => $request->organization_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_person' => $request->contact_person,
            'position' => $request->position,
            'cooperation_type' => $request->jenis_kerjasama,
            'proposal_summary' => $request->proposal_summary,
            'document_path' => $documentPath,
            'status' => $request->status ?? $partnership->status,
        ]);

        return redirect()->route('admin.partnerships.index')
            ->with('success', 'Proposal kerja sama berhasil diperbarui!');
    }

    /**
     * Hapus proposal
     */
    public function destroy(PartnershipProposal $partnership)
    {
        if ($partnership->document_path) {
            Storage::disk('public')->delete($partnership->document_path);
        }

        $partnership->delete();

        return redirect()->route('admin.partnerships.index')
            ->with('success', 'Proposal kerja sama berhasil dihapus.');
    }
}
