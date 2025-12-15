<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            // relasi ke users (supaya index() yang pakai user_id tidak error)
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // biodata tambahan
            $table->string('asal_sekolah')->nullable()->after('peserta');
            $table->string('nim')->nullable()->after('asal_sekolah');
            $table->string('email')->nullable()->after('nim');
            $table->string('phone')->nullable()->after('email');

            // deskripsi prestasi
            $table->text('deskripsi')->nullable()->after('tahun');

            // foto opsional
            $table->string('certificate')->nullable()->after('sertifikat');

            // foreign key ke users (opsional tapi bagus)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'asal_sekolah',
                'nim',
                'email',
                'phone',
                'deskripsi',
                'certificate',
            ]);
        });
    }
};
