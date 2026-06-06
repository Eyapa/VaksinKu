<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_vaksins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluargas')->cascadeOnDelete();
            $table->foreignId('vaksin_id')->constrained('vaksins')->cascadeOnDelete();
            $table->foreignId('faskes_id')->nullable()->constrained('fasilitas_kesehatans')->nullOnDelete();
            $table->integer('nomor_dosis')->default(1);
            $table->date('tanggal_vaksin');
            $table->string('nomor_batch')->nullable();
            $table->string('nama_tenaga_medis')->nullable();
            $table->string('nomor_sertifikat')->nullable()->unique();
            $table->enum('status', ['Selesai', 'Memproses', 'Ditolak', 'Dijadwalkan'])->default('Memproses');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['anggota_keluarga_id', 'vaksin_id']);
            $table->index('tanggal_vaksin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_vaksins');
    }
};
