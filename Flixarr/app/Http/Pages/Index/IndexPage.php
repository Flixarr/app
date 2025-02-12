<?php

namespace App\Http\Pages\Index;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.minimal')]
class IndexPage extends Component
{
    public function render()
    {
        return view('pages.index.index-page');
    }
}
