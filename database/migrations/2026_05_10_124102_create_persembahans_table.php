<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persembahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_pemberi');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('jenis', ['persembahan', 'perpuluhan', 'diakonia', 'misi', 'lainnya'])->default('persembahan');
            $table->decimal('jumlah', 12, 0);
            $table->string('catatan')->nullable();
            $table->string('order_id')->unique();
            $table->string('payment_type')->nullable();  // gopay, qris, dll
            $table->string('transaction_id')->nullable(); // ID dari Midtrans
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel', 'deny'])->default('pending');
            $table->json('snap_token')->nullable(); // token Midtrans
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persembahans');
    }
};