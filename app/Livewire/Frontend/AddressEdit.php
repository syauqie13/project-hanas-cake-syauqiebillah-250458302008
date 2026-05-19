<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\CustomerAddress;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.ecommerce')]
class AddressEdit extends Component
{
    public $address_id;
    public $title;
    public $detail_address;
    public $receiver_name;
    public $receiver_phone;
    public $latitude;
    public $longitude;
    public $is_primary;

    protected $rules = [
        'title' => 'required|string|max:255',
        'receiver_name' => 'required|string|max:255',
        'receiver_phone' => 'required|string|max:20',
        'latitude' => 'required',
        'longitude' => 'required',
    ];

    public function mount($id)
    {
        // Pastikan alamat yang diedit benar-benar milik user yang sedang login (Keamanan)
        $customer = Auth::user()->customer;
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        // Isi properti dengan data lama
        $this->address_id = $address->id;
        $this->title = $address->title;
        $this->detail_address = $address->detail_address;
        $this->receiver_name = $address->receiver_name;
        $this->receiver_phone = $address->receiver_phone;
        $this->latitude = $address->latitude;
        $this->longitude = $address->longitude;
        $this->is_primary = $address->is_primary;
    }

    #[On('update-coordinates')]
    public function setCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Titik lokasi berhasil diperbarui!']);
    }

    public function updateAddress()
    {
        $this->validate();
        $customer = Auth::user()->customer;

        // Atur ulang primary jika dicentang
        if ($this->is_primary) {
            CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $this->address_id)
                ->update(['is_primary' => false]);
        }

        // Update data
        CustomerAddress::where('id', $this->address_id)->update([
            'title' => $this->title,
            'detail_address' => $this->detail_address,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_primary' => $this->is_primary,
        ]);

        // Opsional: Langsung jadikan alamat ini yang aktif di session
        session()->put('selected_address_id', $this->address_id);

        return $this->redirect(route('pelanggan.alamat'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.address-edit');
    }
}