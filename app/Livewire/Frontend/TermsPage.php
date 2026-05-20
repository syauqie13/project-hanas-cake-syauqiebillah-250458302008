<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Syarat & Ketentuan')]
class TermsPage extends Component
{
    public function render()
    {
        return view('livewire.frontend.terms-page');
    }
}
