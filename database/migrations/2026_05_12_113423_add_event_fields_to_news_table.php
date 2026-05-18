<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->boolean('is_event')->default(false)->after('is_published');
            $table->string('lokasi_acara')->nullable()->after('is_event');
            $table->dateTime('tanggal_acara')->nullable()->after('lokasi_acara');
            $table->dateTime('tanggal_acara_selesai')->nullable()->after('tanggal_acara');
            $table->integer('kuota')->nullable()->after('tanggal_acara_selesai');
            $table->boolean('buka_pendaftaran')->default(false)->after('kuota');
            $table->dateTime('batas_pendaftaran')->nullable()->after('buka_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'is_event', 'lokasi_acara', 'tanggal_acara',
                'tanggal_acara_selesai', 'kuota',
                'buka_pendaftaran', 'batas_pendaftaran',
            ]);
        });
    }
};