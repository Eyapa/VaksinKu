<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaksins', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->string('jenis');
            $table->integer('dosis_total');
            $table->integer('interval_hari')->default(0);
            $table->integer('usia_minimal_bulan')->default(0);
            $table->integer('usia_maksimal_tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('produsen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaksins');
    }
};
