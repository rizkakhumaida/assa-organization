<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',       // siapa yang setor prestasi (anggota)
        'peserta',       // nama lengkap peserta
        'asal_sekolah',  // sekolah/universitas
        'nim',           // NIM / NISN
        'email',         // email peserta
        'phone',         // nomor HP peserta

        'prestasi',      // nama / judul prestasi
        'tingkat',       // nasional, provinsi, internasional, dll
        'kategori',      // akademik, seni, olahraga, dll
        'tahun',         // tanggal prestasi (date)
        'deskripsi',     // deskripsi singkat prestasi

        'poin',          // poin—dipakai admin untuk scoring
        'sertifikat',    // path file sertifikat/piagam
        'certificate',   // path foto prestasi (opsional)

        'status',        // Verified / Pending / Rejected
        'aksi',          // kolom aksi (opsional)
    ];

    protected $casts = [
        'tahun' => 'date',  // otomatis menjadi Carbon instance
    ];

    /**
     * Relasi: prestasi dimiliki oleh 1 user (anggota)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
