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
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->dropColumn('no_kartu_vaksin');
            $table->string('hubungan_lainnya')->nullable()->after('hubungan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->string('no_kartu_vaksin')->nullable();
            $table->dropColumn('hubungan_lainnya');
        });
    }
};
