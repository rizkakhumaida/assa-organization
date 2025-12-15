<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_proposals', function (Blueprint $table) {
            $table->id();

            // User pengajuan
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Identitas Instansi
            $table->string('organization_name', 150);   // Nama Instansi
            $table->string('contact_email');            // Email Resmi
            $table->text('address');                    // Alamat Instansi
            $table->string('contact_phone', 30);        // Nomor Telepon

            // Penanggung Jawab (PIC)
            $table->string('contact_person', 120);      // Nama PIC
            $table->string('position', 120);            // Jabatan PIC

            // Jenis Kerjasama
            $table->enum('cooperation_type', [
                'Beasiswa',
                'Sponsorship',
                'Kegiatan',
                'Seminar',
                'Event',
                'Penelitian',
                'Magang',
                'Lainnya'
            ]);

            // Ringkasan proposal
            $table->text('proposal_summary');           // Isi deskripsi / ringkasan

            // Dokumen proposal
            $table->string('document_path')->nullable(); // File path di storage

            // Status pengajuan
            $table->enum('status', [
                'submitted',
                'review',
                'pending',
                'approved',
                'rejected',
                'onhold'
            ])->default('submitted');

            // Catatan admin
            $table->text('notes')->nullable();

            $table->timestamps();

            // Index untuk mempercepat pencarian admin
            $table->index(['status', 'cooperation_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_proposals');
    }
};
