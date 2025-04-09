<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public $idModal;
    public $modalType;

    public function __construct($title = null, $idModal, $modalType = null)
    {
        $this->title = $title;
        $this->idModal = $idModal;
        $this->modalType = $modalType;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal');
    }
}
