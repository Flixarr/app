<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Minimal extends Component
{
    public string $title;
    public bool $hide_logo;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title, bool $hide_logo = false)
    {
        $this->title =  $title;
        $this->hide_logo = $hide_logo;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.minimal');
    }
}
