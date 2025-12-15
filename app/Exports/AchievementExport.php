<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class AchievementExport implements FromCollection
{
    protected $achievements;

    public function __construct($achievements)
    {
        $this->achievements = $achievements;
    }

    public function collection()
    {
        return $this->achievements->map(function($a){
            return [
                'ID' => $a->id,
                'Judul Prestasi' => $a->title,
                'Deskripsi' => $a->description,
                'Poin' => $a->points,
                'Tanggal' => $a->achieved_at?->format('d-m-Y'),
                'Dibuat' => $a->created_at->format('d-m-Y'),
            ];
        });
    }
}

