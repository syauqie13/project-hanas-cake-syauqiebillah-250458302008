<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\CustomerAddress;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.ecommerce')]
class AddressSelection extends Component
{
    public $search = '';
    public $activeTab = 'tersimpan'; // 'terakhir' or 'tersimpan'

    public function selectAddress($addressId)
    {
        Session::put('selected_address_id', $addressId);
        
        // Also update mode to delivery if they select an address
        Session::put('delivery_mode', 'delivery');
        
        return $this->redirect('/ecommerce', navigate: true);
    }

    public function render()
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            $customer = \App\Models\Customer::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
        }

        $query = CustomerAddress::where('customer_id', $customer->id);

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('detail_address', 'like', '%' . $this->search . '%');
            });
        }

        // Kalau tab 'terakhir', urutkan berdasarkan updated_at desc
        if ($this->activeTab == 'terakhir') {
            $query->orderBy('updated_at', 'desc');
        } else {
            $query->orderBy('is_primary', 'desc')->orderBy('created_at', 'asc');
        }

        $addresses = $query->get();

        return view('livewire.frontend.address-selection', [
            'addresses' => $addresses
        ]);
    }
}
