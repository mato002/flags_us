<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthPortalLayout extends Component
{
    public function __construct(
        public string $title = '',
    ) {}

    public function render(): View
    {
        return view('layouts.auth-portal', [
            'layoutTitle' => $this->title,
        ]);
    }
}
