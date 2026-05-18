<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\CustomerAddress;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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

    // Tambahkan validasi untuk latitude dan longitude
    protected $rules = [
        'title' => 'required|string|max:255',
        'receiver_name' => 'required|string|max:255',
        'receiver_phone' => 'required|string|max:20',
        'latitude' => 'required',
        'longitude' => 'required',
    ];

    protected $messages = [
        'title.required' => 'Judul alamat harus diisi (contoh: Rumah, Kantor).',
        'receiver_name.required' => 'Nama penerima harus diisi.',
        'receiver_phone.required' => 'Nomor telepon harus diisi.',
        'latitude.required' => 'Gagal mendapatkan titik lokasi GPS. Pastikan izin lokasi aktif.',
    ];

    // Fungsi untuk menangkap koordinat dari Javascript (Blade)
    #[On('update-coordinates')]
    public function setCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function saveAddress()
    {
        // Jika latitude/longitude kosong, sistem akan menahan submit & memunculkan pesan error
        $this->validate();

        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            $customer = \App\Models\Customer::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
        }

        if ($this->is_primary) {
            CustomerAddress::where('customer_id', $customer->id)->update(['is_primary' => false]);
        }
        
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
            // Koordinat sekarang dijamin masuk ke database
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_primary' => $this->is_primary,
        ]);

        Session::put('selected_address_id', $address->id);
        Session::put('delivery_mode', 'delivery');

        // Pastikan membawa parameter mode ke halaman shop agar kalkulasi berjalan
        return $this->redirect(route('ecommerce', ['mode' => 'delivery']), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.address-create');
    }
}