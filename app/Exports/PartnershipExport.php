<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class PartnershipExport implements FromCollection
{
    protected $proposals;

    public function __construct($proposals)
    {
        $this->proposals = $proposals;
    }

    public function collection()
    {
        return $this->proposals->map(function($p) {
            return [
                'ID' => $p->id,
                'Nama Organisasi' => $p->organization_name,
                'Email' => $p->contact_email,
                'Telepon' => $p->contact_phone,
                'Ringkasan' => $p->proposal_summary,
                'Status' => $p->status,
                'Tanggal' => $p->created_at->format('d-m-Y H:i'),
            ];
        });
    }
}

