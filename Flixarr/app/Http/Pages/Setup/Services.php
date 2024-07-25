<?php

namespace App\Http\Pages\Setup;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Services extends Component
{
    public int $step;

    #[Layout('layouts.minimal', ['title' => 'Services'])]
    public function render()
    {
        return view('pages.setup.services');
    }

    function load(): void
    {
        // $this->step = 1;
    }
}
