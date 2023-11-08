<?php

namespace App\Http\Pages\Setup;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SetupPage extends Component
{

    /**
     * Page State (str) | "signin", "servers", "services"
     *
     * The state in which the setup process is in. 
     */
    public $state;

    #[Layout('layouts.minimal', ['title' => 'Setup'])]
    public function render()
    {
        return view('pages.setup.setup-page');
    }

    public function mount()
    {
        $this->state = 'signin';
    }
}
