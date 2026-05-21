<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jemaat_id')->constrained('jemaats')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['reminder_ibadah', 'konfirmasi_absensi', 'pengumuman'])->default('pengumuman');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable(); // data tambahan seperti schedule_id
            $table->timestamps();

            $table->index(['jemaat_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
