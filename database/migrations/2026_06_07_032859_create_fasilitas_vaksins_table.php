<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fasilitas_vaksins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faskes_id')->constrained('fasilitas_kesehatans')->cascadeOnDelete();
            $table->foreignId('vaksin_id')->constrained('vaksins')->cascadeOnDelete();
            $table->enum('status', ['Tersedia', 'Hampir Penuh', 'Habis'])->default('Tersedia');
            $table->timestamps();

            // Prevent duplicate entries for the same faskes and vaksin
            $table->unique(['faskes_id', 'vaksin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_vaksins');
    }
};
