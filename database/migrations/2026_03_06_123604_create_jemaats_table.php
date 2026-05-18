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
        Schema::create('jemaats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data Pribadi
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('nomor_hp');

            // Data Gereja
            $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Duda', 'Janda']);
            $table->date('tanggal_baptis')->nullable();
            $table->enum('status_aktif', ['Aktif', 'Tidak Aktif'])->default('Aktif');

            // File & Identitas
            $table->string('no_identitas')->unique();
            $table->string('foto')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemaats');
    }
};
