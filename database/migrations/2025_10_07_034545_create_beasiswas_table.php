<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();

            // Tetap menyimpan user_id agar tahu siapa yang input (boleh dihapus jika tidak perlu)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Struktur baru sesuai permintaan
            $table->string('nama_pendaftar');  // Nama Pendaftar
            $table->string('campus');          // Campus / Kampus Asal
            $table->string('program_studi');   // Program Studi
            $table->string('dokumen')->nullable(); // Dokumen upload (PDF/JPG/PNG)

            // Status ENUM: Pending, Approved, Rejected, On Hold
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On Hold'])->default('Pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};
