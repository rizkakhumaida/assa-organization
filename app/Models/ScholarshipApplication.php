<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $table = 'scholarship_applications';

    /**
     * Kolom yang dapat diisi massal.
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'campus',
        'reason',

        // Dokumen upload
        'file_ktp',
        'file_kk',
        'file_ijazah_transkrip',
        'file_kip_pkh',
        'file_sktm',
        'file_prestasi',
        'status',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor status berformat huruf besar diawal
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst(strtolower($this->status));
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
