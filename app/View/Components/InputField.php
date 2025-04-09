<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputField extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $type;
    public $name;
    public $isReadOnly;

    public function __construct($label, $type, $name, $isReadOnly = false)
    {
        $this->label = $label;
        $this->type = $type;
        $this->name = $name;
        $this->isReadOnly = $isReadOnly;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-field');
    }
}
