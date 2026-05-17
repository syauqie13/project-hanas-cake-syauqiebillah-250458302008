<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\CustomerAddress;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.ecommerce')]
class AddressCreate extends Component
{
    public $title = '';
    public $detail_address = '';
    public $receiver_name = '';
    public $receiver_phone = '';
    public $latitude;
    public $longitude;
    public $is_primary = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'receiver_name' => 'required|string|max:255',
        'receiver_phone' => 'required|string|max:20',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ];

    protected $messages = [
        'title.required' => 'Judul alamat harus diisi (contoh: Rumah, Kantor).',
        'receiver_name.required' => 'Nama penerima harus diisi.',
        'receiver_phone.required' => 'Nomor telepon harus diisi.',
        'latitude.required' => 'Lokasi GPS belum didapatkan. Silakan klik tombol Ambil Koordinat GPS.',
    ];

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        
        $this->dispatch('notify', [
            'message' => 'Koordinat GPS berhasil didapatkan!',
            'icon' => 'success'
        ]);
    }

    public function saveAddress()
    {
        $this->validate();

        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            $customer = \App\Models\Customer::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
        }

        // Kalau ini diset primary, unset primary yang lain
        if ($this->is_primary) {
            CustomerAddress::where('customer_id', $customer->id)->update(['is_primary' => false]);
        }
        
        // Kalau belum punya alamat sama sekali, jadikan primary otomatis
        $addressCount = CustomerAddress::where('customer_id', $customer->id)->count();
        if ($addressCount === 0) {
            $this->is_primary = true;
        }

        $address = CustomerAddress::create([
            'customer_id' => $customer->id,
            'title' => $this->title,
            'detail_address' => $this->detail_address,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_primary' => $this->is_primary,
        ]);

        // Jadikan alamat yang baru dibuat sebagai alamat terpilih saat ini
        Session::put('selected_address_id', $address->id);
        Session::put('delivery_mode', 'delivery');

        return $this->redirect('/ecommerce', navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.address-create');
    }
}
