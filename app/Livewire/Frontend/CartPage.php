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

    public function updateQuantity($productId, $quantity)
    {
        $quantity = max(1, (int) $quantity);
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    // ===========================================
    // === 2. ALERT "HARUS LOGIN" (BACKEND) ===
    // ===========================================
    /**
     * Method ini dipanggil oleh tombol "Checkout"
     * jika pelanggan belum login.
     */
    public function showLoginWarning()
    {
        // Kirim event ke JavaScript untuk memunculkan SweetAlert
        $this->dispatch('showLoginWarning');
    }

    public function render()
    {
        return view('livewire.frontend.cart-page');
    }
}
