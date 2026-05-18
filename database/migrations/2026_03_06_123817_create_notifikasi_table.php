<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jemaat_id')->constrained('jemaats')->onDelete('cascade');
            $table->string('judul');
            $table->text('isi');
            $table->enum('tipe', ['Pengumuman', 'Undangan', 'Pemberitahuan']);
            $table->timestamp('tanggal_kirim');
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
