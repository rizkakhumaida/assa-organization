<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnershipProposal extends Model
{
    protected $fillable = [
        'user_id',
        'organization_name',   // Nama Instansi
        'contact_email',       // Email Resmi
        'address',             // Alamat Instansi
        'contact_phone',       // Nomor Telepon
        'contact_person',      // Nama Penanggung Jawab
        'position',            // Jabatan
        'cooperation_type',    // Jenis Kerjasama
        'proposal_summary',    // Deskripsi Kerjasama
        'document_path',       // Path File Proposal
        'status',
        'notes',
    ];
}
