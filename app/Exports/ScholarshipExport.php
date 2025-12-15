<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ScholarshipExport implements FromCollection
{
    protected $scholarships;

    public function __construct($scholarships)
    {
        $this->scholarships = $scholarships;
    }

    public function collection()
    {
        return $this->scholarships->map(function($s){
            return [
                'ID' => $s->id,
                'Nama Beasiswa' => $s->nama_beasiswa,
                'Penyelenggara' => $s->penyelenggara,
                'Deskripsi' => $s->deskripsi,
                'Tanggal Pengajuan' => $s->tanggal_pengajuan,
                'Status' => $s->status,
            ];
        });
    }
}

