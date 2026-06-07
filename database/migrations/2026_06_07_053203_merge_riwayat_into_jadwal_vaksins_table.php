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
        Schema::table('jadwal_vaksins', function (Blueprint $table) {
            $table->integer('nomor_dosis')->default(1)->after('faskes_id');
            $table->string('nomor_batch')->nullable()->after('tanggal_jadwal');
            $table->string('nama_tenaga_medis')->nullable()->after('nomor_batch');
            $table->string('nomor_sertifikat')->nullable()->unique()->after('nama_tenaga_medis');
            $table->text('catatan')->nullable()->after('nomor_sertifikat');
            $table->string('file_sertifikat_url')->nullable()->after('catatan');
        });

        // Drop riwayat_vaksins table if exists
        Schema::dropIfExists('riwayat_vaksins');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_vaksins', function (Blueprint $table) {
            $table->dropColumn(['nomor_dosis', 'nomor_batch', 'nama_tenaga_medis', 'nomor_sertifikat', 'catatan']);
        });

        // Note: Recreating riwayat_vaksins would be manual or omitted for this down migration
    }
};
