<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('cashier_id')
                ->constrained('users')
                ->onDelete('set null'); // Jika user dihapus, ordernya tetap ada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign(['user_id']);
            // Hapus kolomnya
            $table->dropColumn('user_id');
        });
    }
};
