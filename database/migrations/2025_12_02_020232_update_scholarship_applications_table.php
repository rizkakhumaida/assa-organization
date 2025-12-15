<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            // Tambah kolom baru
            $table->string('full_name');
            $table->string('email');
            $table->text('reason');

            // Dokumen upload
            $table->string('file_ktp')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_ijazah_transkrip')->nullable();
            $table->string('file_kip_pkh')->nullable();
            $table->string('file_sktm')->nullable();
            $table->string('file_prestasi')->nullable();
        });

        // Drop kolom lama harus di luar closure dan satu per satu
        Schema::table('scholarship_applications', function (Blueprint $table) {
            $table->dropColumn('nama_pendaftar');
        });
        Schema::table('scholarship_applications', function (Blueprint $table) {
            $table->dropColumn('program_studi');
        });
        Schema::table('scholarship_applications', function (Blueprint $table) {
            $table->dropColumn('dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->string('nama_pendaftar')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('dokumen')->nullable();

            // Hapus kolom baru
            $table->dropColumn([
                'full_name',
                'email',
                'reason',
                'file_ktp',
                'file_kk',
                'file_ijazah_transkrip',
                'file_kip_pkh',
                'file_sktm',
                'file_prestasi',
            ]);
        });
    }
};


