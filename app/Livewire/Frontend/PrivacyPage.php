<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ecommerce')]
#[Title('Kebijakan Privasi')]
class PrivacyPage extends Component
{
    public function render()
    {
        return view('livewire.frontend.privacy-page');
    }
}
