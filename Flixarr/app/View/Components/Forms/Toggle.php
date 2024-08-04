<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toggle extends Component
{
    public string $label;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Closure|View|string
    {
        return view('components.forms.toggle');
    }
}
