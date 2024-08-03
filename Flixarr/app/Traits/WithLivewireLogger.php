<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Usernotnull\Toast\Concerns\WireToast;

trait WithLivewireLogger
{
    use WireToast;

    public function mount()
    {
        logAction('App', 'Page load', [
            collect(Route::getCurrentRoute())->toArray(),
        ]);
    }
}
