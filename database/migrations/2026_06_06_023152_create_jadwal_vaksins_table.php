<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_vaksins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluargas')->cascadeOnDelete();
            $table->foreignId('vaksin_id')->constrained('vaksins')->cascadeOnDelete();
            $table->foreignId('faskes_id')->nullable()->constrained('fasilitas_kesehatans')->nullOnDelete();
            $table->date('tanggal_jadwal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('nomor_antrian')->nullable();
            $table->enum('status', ['terdaftar', 'konfirmasi', 'selesai', 'batal'])->default('terdaftar');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['anggota_keluarga_id', 'vaksin_id']);
            $table->index('tanggal_jadwal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_vaksins');
    }
};
