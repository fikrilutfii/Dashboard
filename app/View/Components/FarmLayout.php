<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FarmLayout extends Component
{
    public ?string $title;
    public ?string $subtitle;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $title = null, ?string $subtitle = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('farm.layouts.app');
    }
}
