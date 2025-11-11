<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Keranjang Belanja')]
class CartPage extends Component
{
    public $cartItems = [];
    public $total = 0;

    /**
     * Muat data keranjang dari Session saat halaman dibuka
     */
    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = session()->get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cartItems as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    /**
     * Update jumlah item di keranjang
     */
    public function updateQuantity($productId, $quantity)
    {
        // Pastikan jumlah minimal 1
        $quantity = max(1, (int)$quantity);

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $this->loadCart(); // Muat ulang data
            $this->dispatch('cartUpdated'); // Kirim sinyal ke Ikon Keranjang
        }
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]); // Hapus item
        session()->put('cart', $cart);

        $this->loadCart(); // Muat ulang data
        $this->dispatch('cartUpdated'); // Kirim sinyal ke Ikon Keranjang
    }

    public function render()
    {
        return view('livewire.frontend.cart-page');
    }
}
