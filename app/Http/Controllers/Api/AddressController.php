<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAddressRequest;
use App\Http\Requests\Api\UpdateAddressRequest;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * AddressController
 *
 * Mengelola CRUD alamat pelanggan untuk aplikasi mobile (Flutter).
 * Setiap pelanggan bisa memiliki banyak alamat (rumah, kantor, dll.)
 * dengan satu alamat utama (is_primary).
 */
class AddressController extends Controller
{
    use ApiResponseTrait;

    /**
     * Mendapatkan customer record berdasarkan user yang login.
     * Jika belum ada record customer, buat otomatis.
     */
    private function getCustomer(Request $request): Customer
    {
        $user = $request->user();

        return Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name'    => $user->name,
                'phone'   => $user->phone,
                'address' => $user->address,
            ]
        );
    }

    /**
     * GET /api/addresses
     *
     * Menampilkan semua alamat milik pelanggan yang login.
     * Alamat utama (is_primary = true) akan ditampilkan di urutan pertama.
     */
    public function index(Request $request): JsonResponse
    {
        $customer  = $this->getCustomer($request);
        $addresses = CustomerAddress::where('customer_id', $customer->id)
            ->orderByDesc('is_primary') // Alamat utama di atas
            ->orderByDesc('created_at')
            ->get();

        return $this->successResponse($addresses, 'Daftar alamat berhasil diambil');
    }

    /**
     * POST /api/addresses
     *
     * Menambahkan alamat baru untuk pelanggan.
     * Jika is_primary = true, semua alamat lain akan di-reset menjadi non-primary.
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        $customer  = $this->getCustomer($request);
        $validated = $request->validated();

        // Jika alamat baru di-set sebagai primary, reset alamat lain
        if (!empty($validated['is_primary']) && $validated['is_primary']) {
            CustomerAddress::where('customer_id', $customer->id)
                ->update(['is_primary' => false]);
        }

        // Jika ini alamat pertama, otomatis jadikan primary
        $existingCount = CustomerAddress::where('customer_id', $customer->id)->count();
        if ($existingCount === 0) {
            $validated['is_primary'] = true;
        }

        $address = CustomerAddress::create([
            'customer_id'    => $customer->id,
            'title'          => $validated['title'],
            'detail_address' => $validated['detail_address'],
            'latitude'       => $validated['latitude'] ?? null,
            'longitude'      => $validated['longitude'] ?? null,
            'receiver_name'  => $validated['receiver_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'is_primary'     => $validated['is_primary'] ?? false,
        ]);

        return $this->successResponse($address, 'Alamat berhasil ditambahkan', 201);
    }

    /**
     * PUT /api/addresses/{id}
     *
     * Mengupdate alamat yang sudah ada.
     * Hanya bisa mengupdate alamat milik pelanggan sendiri.
     */
    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        $customer = $this->getCustomer($request);
        $address  = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$address) {
            return $this->notFoundResponse('Alamat tidak ditemukan');
        }

        $validated = $request->validated();

        // Jika di-set sebagai primary, reset alamat lain
        if (!empty($validated['is_primary']) && $validated['is_primary']) {
            CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $id)
                ->update(['is_primary' => false]);
        }

        $address->update($validated);

        return $this->successResponse($address->fresh(), 'Alamat berhasil diperbarui');
    }

    /**
     * DELETE /api/addresses/{id}
     *
     * Menghapus alamat pelanggan.
     * Jika yang dihapus adalah alamat primary, alamat terbaru akan dijadikan primary.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $customer = $this->getCustomer($request);
        $address  = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$address) {
            return $this->notFoundResponse('Alamat tidak ditemukan');
        }

        $wasPrimary = $address->is_primary;
        $address->delete();

        // Jika yang dihapus adalah primary, set alamat terbaru sebagai primary baru
        if ($wasPrimary) {
            $nextAddress = CustomerAddress::where('customer_id', $customer->id)
                ->orderByDesc('created_at')
                ->first();

            if ($nextAddress) {
                $nextAddress->update(['is_primary' => true]);
            }
        }

        return $this->successResponse(null, 'Alamat berhasil dihapus');
    }

    /**
     * PATCH /api/addresses/{id}/primary
     *
     * Set alamat tertentu sebagai alamat utama.
     * Semua alamat lain akan di-reset menjadi non-primary.
     */
    public function setPrimary(Request $request, int $id): JsonResponse
    {
        $customer = $this->getCustomer($request);
        $address  = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$address) {
            return $this->notFoundResponse('Alamat tidak ditemukan');
        }

        // Reset semua alamat lain, lalu set yang ini sebagai primary
        CustomerAddress::where('customer_id', $customer->id)
            ->update(['is_primary' => false]);

        $address->update(['is_primary' => true]);

        return $this->successResponse($address->fresh(), 'Alamat utama berhasil diubah');
    }
}
