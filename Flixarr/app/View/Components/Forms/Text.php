<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{
    public string $wiremodel;
    public string $label;
    public string $type;
    public string $placeholder;

    /**
     * Create a new component instance.
     */
    public function __construct($wiremodel, $label, $type = null, $placeholder = null)
    {
        $this->wiremodel = $wiremodel;
        $this->label = $label;
        $this->type = $type ?? 'text';
        $this->placeholder = $placeholder ?? $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.text');
    }
}
