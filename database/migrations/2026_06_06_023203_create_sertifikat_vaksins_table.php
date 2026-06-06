<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat_vaksins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluargas')->cascadeOnDelete();
            $table->foreignId('vaksin_id')->constrained('vaksins')->cascadeOnDelete();
            $table->string('nomor_sertifikat')->unique();
            $table->text('qr_code_data')->nullable();
            $table->date('tanggal_terbit');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['anggota_keluarga_id', 'vaksin_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat_vaksins');
    }
};
