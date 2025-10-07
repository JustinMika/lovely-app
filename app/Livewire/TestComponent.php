<?php

namespace App\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public $message = 'Test initial';
    public $counter = 0;

    public function increment()
    {
        $this->counter++;
        $this->message = 'Compteur: ' . $this->counter;
        session()->flash('success', 'Bouton cliqué ! Compteur: ' . $this->counter);
    }

    public function render()
    {
        return view('livewire.test-component');
    }
}
