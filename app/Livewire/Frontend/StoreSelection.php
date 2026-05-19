<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class StoreSelection extends Component
{
    public $mode = 'pickup';
    public $userLat = null;
    public $userLng = null;

    public function mount()
    {
        $this->mode = request()->query('mode', 'pickup');

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->customer) {
            $customer = \Illuminate\Support\Facades\Auth::user()->customer;
            $address = null;

            // 1. Coba ambil dari session alamat yang sedang dipilih (Sama seperti halaman Shop)
            if (session()->has('selected_address_id')) {
                $address = \App\Models\CustomerAddress::find(session('selected_address_id'));
            }

            // 2. Jika tidak ada di session, ambil alamat utamanya (primary)
            if (!$address) {
                $address = \App\Models\CustomerAddress::where('customer_id', $customer->id)
                    ->orderBy('is_primary', 'desc')
                    ->first();
            }

            // 3. Jika alamat pengiriman ada, gunakan koordinat dari alamat tersebut!
            if ($address && $address->latitude && $address->longitude) {
                $this->userLat = $address->latitude;
                $this->userLng = $address->longitude;
            }
            // 4. Fallback ke tabel customer lama (hanya jika user belum pernah buat alamat)
            else {
                $this->userLat = $customer->latitude;
                $this->userLng = $customer->longitude;
            }
        }
    }

    #[On('location-updated')]
    public function updateLocation($lat, $lng)
    {
        $this->userLat = $lat;
        $this->userLng = $lng;

        if (Auth::check() && Auth::user()->customer) {
            Auth::user()->customer->update([
                'latitude' => $lat,
                'longitude' => $lng
            ]);
        }
    }

    public function selectStore($storeId)
    {
        session()->put('selected_store_id', $storeId);
        return $this->redirect(route('ecommerce', ['mode' => $this->mode]), navigate: true);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $dist = $earthRadius * $c;
        return round($dist, 2);
    }

    public function render()
    {
        $stores = Store::where('is_active', true)->get();

        if ($this->userLat && $this->userLng) {
            foreach ($stores as $store) {
                if ($store->latitude && $store->longitude) {
                    $store->distance = $this->calculateDistance($this->userLat, $this->userLng, $store->latitude, $store->longitude);
                } else {
                    // Fallback distance if store has no coordinates
                    $store->distance = 9999;
                }
            }
            $stores = $stores->sortBy('distance')->values();
        } else {
            // Add a temporary null distance property so view doesn't break
            foreach ($stores as $store) {
                $store->distance = null;
            }
        }

        return view('livewire.frontend.store-selection', compact('stores'))
            ->layout('components.layouts.guest');
    }
}
