<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $count = 0;

    public function mount()
    {
        // Muat jumlah saat halaman pertama kali dibuka
        $this->updateCount();
    }

    /**
     * Listener ini "mendengar" event 'cartUpdated'
     * yang dikirim dari Shop.php atau CartPage.php
     */
    #[On('cartUpdated')]
    public function updateCount()
    {
        $cart = session()->get('cart', []);

        // Menghitung total *jumlah* (quantity), bukan cuma jumlah produk
        $this->count = collect($cart)->sum('quantity');
    }

    public function render()
    {
        return view('livewire.frontend.cart-counter');
    }
}
