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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode Voucher
            $table->enum('type', ['nominal', 'percentage']); // Tipe diskon
            $table->integer('value'); // Nilai diskon (Rp atau %)
            $table->integer('min_purchase')->nullable(); // Minimal belanja
            $table->integer('max_discount')->nullable(); // Maksimal diskon (untuk persentase)
            $table->dateTime('valid_until')->nullable(); // Masa berlaku
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
