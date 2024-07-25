<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Logo extends Component
{
    public $size;

    /**
     * Create a new component instance.
     */
    public function __construct($size = null)
    {
        $this->size = $size ?? 'text-4xl';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.logo');
    }
}
