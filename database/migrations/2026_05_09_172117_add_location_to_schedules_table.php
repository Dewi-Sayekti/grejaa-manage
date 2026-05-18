<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('location')->nullable()->after('day');
            $table->date('tanggal')->nullable()->after('location'); // tanggal spesifik (untuk event sekali)
            $table->boolean('is_recurring')->default(true)->after('tanggal'); // rutin mingguan atau sekali
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['location', 'tanggal', 'is_recurring']);
        });
    }
};