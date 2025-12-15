<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
   {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();

            $table->string('peserta'); // Nama peserta
            $table->string('prestasi'); // Nama prestasi
            $table->string('tingkat')->nullable(); // Nasional, Provinsi, Internasional
            $table->string('kategori')->nullable(); // Akademik, Non-Akademik, Seni
            $table->date('tahun')->nullable(); // Tahun atau tanggal pencapaian
            $table->integer('poin')->nullable()->default(0); // ✅ Tambahan kolom poin
            $table->string('sertifikat')->nullable(); // Path sertifikat
            $table->enum('status', ['Verified', 'Pending', 'Rejected'])->default('Pending');
            $table->string('aksi')->nullable(); // Kolom aksi (opsional)
            $table->timestamps();
        });
   }

   public function down(): void
   {
        Schema::dropIfExists('achievements');
   }
};
