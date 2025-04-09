<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextAreaField extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $name;
    public $isReadOnly;
    public $cols;
    public $rows;

    public function __construct($label, $name, $isReadOnly = false, $cols = 2, $rows = 2)
    {
        $this->label = $label;
        $this->name = $name;
        $this->isReadOnly = $isReadOnly;
        $this->cols = $cols;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.text-area-field');
    }
}
