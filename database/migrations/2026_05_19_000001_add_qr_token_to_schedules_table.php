<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom QR token ke table schedules
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('is_active');
            $table->timestamp('qr_expires_at')->nullable()->after('qr_token');
        });

        // Tambah kolom input_by ke absensis (untuk absensi massal oleh admin)
        Schema::table('absensis', function (Blueprint $table) {
            $table->foreignId('input_by')->nullable()->constrained('users')->onDelete('set null')->after('catatan_admin');
            $table->enum('input_method', ['self', 'admin', 'qr'])->default('self')->after('input_by');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'qr_expires_at']);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['input_by']);
            $table->dropColumn(['input_by', 'input_method']);
        });
    }
};
