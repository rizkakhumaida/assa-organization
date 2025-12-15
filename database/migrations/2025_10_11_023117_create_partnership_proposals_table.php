<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partnership_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Identitas instansi & kontak
            $table->string('organization_name', 150);
            $table->string('contact_email');
            $table->string('contact_phone', 30)->nullable();

            // PIC (penanggung jawab)
            $table->string('contact_person', 120)->nullable();
            $table->string('position', 120)->nullable();

            // Jenis kerja sama
            $table->enum('cooperation_type', [
                'Beasiswa',
                'Sponsorship',
                'Kegiatan',
                'Seminar',
                'Event',
                'Penelitian',
                'Magang',
                'Lainnya'
            ])->default('Lainnya');

            // Ringkasan & dokumen
            $table->text('proposal_summary');
            $table->string('document_path')->nullable(); // storage path

            // Status & catatan admin
            $table->enum('status', [
                'submitted',
                'review',
                'pending',
                'approved',
                'rejected',
                'onhold'
            ])->default('submitted');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'cooperation_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_proposals');
    }
};
