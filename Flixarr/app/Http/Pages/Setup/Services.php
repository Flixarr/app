<?php

namespace App\Http\Pages\Setup;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Services extends Component
{
    public int $step;
    public $radarr = [];
    public $sonarr = [];

    #[Layout('layouts.app', ['title' => 'Services'])]
    public function render()
    {
        return view('pages.setup.services');
    }

    public function load(): void
    {
        // $this->step = 1;
    }
}
