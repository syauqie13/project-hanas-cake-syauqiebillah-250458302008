<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Order;
use App\Models\Store;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Pembayaran Berhasil')]
class OrderSuccessPage extends Component
{
    public Order $order;
    public $store = null;
    public $distance = null;
    public $queueNumber = '000';

    public function mount(Order $order)
    {
        // 1. Keamanan: Pastikan hanya pemilik order yang bisa melihat
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $this->order = $order;

        // 2. Cari data store berdasarkan shipping_city (tempat nama store disimpan)
        $storeName = $order->shipping_city;
        $this->store = Store::where('name', $storeName)->first() 
            ?? Store::where('is_active', true)->first();

        // 3. Hitung jarak jika pelanggan memiliki koordinat alamat
        if ($this->store && Auth::check()) {
            $customer = Auth::user()->customer;
            if ($customer) {
                $addressModel = CustomerAddress::where('customer_id', $customer->id)->where('is_primary', true)->first()
                    ?? CustomerAddress::where('customer_id', $customer->id)->first();
                
                if ($addressModel && $this->store->latitude && $addressModel->latitude) {
                    $this->distance = $this->calculateDistance(
                        $addressModel->latitude, 
                        $addressModel->longitude, 
                        $this->store->latitude, 
                        $this->store->longitude
                    );
                }
            }
        }

        // 4. Generate nomor antrean 3 digit berbasis ID pesanan
        $this->queueNumber = sprintf('%03d', $order->id % 1000);
        if ($this->queueNumber === '000') {
            $this->queueNumber = '999';
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) 
    {
        $earthRadius = 6371;
        $dLat = deg2rad((float)$lat2 - (float)$lat1);
        $dLon = deg2rad((float)$lon2 - (float)$lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }

    public function render()
    {
        return view('livewire.frontend.order-success-page');
    }
}
