<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaats')->onDelete('set null');
            $table->enum('tipe', ['Pemasukan', 'Pengeluaran']);
            $table->string('kategori');
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->string('bukti_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
