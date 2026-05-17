<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('title')->default('Lokasimu Saat Ini');
            $table->text('detail_address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Pindahkan data lama dari tabel customers
        $customers = DB::table('customers')->get();
        foreach ($customers as $customer) {
            if ($customer->latitude && $customer->longitude) {
                DB::table('customer_addresses')->insert([
                    'customer_id' => $customer->id,
                    'title' => 'Alamat Utama',
                    'detail_address' => $customer->detail_address,
                    'latitude' => $customer->latitude,
                    'longitude' => $customer->longitude,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
