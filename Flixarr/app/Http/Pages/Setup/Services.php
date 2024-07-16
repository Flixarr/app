<?php

namespace App\Http\Pages\Setup;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Services extends Component
{
    #[Layout('layouts.minimal', ['title' => 'Services'])]
    public function render()
    {
        return view('pages.setup.services');
    }

    public function load(): void
    {
        //
    }
}
