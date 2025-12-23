<?php

namespace App\Http\Middleware;

/**
 * Lightweight alias that defers to framework RedirectIfAuthenticated.
 */
class RedirectIfAuthenticated extends \Illuminate\Auth\Middleware\RedirectIfAuthenticated
{
    // default behavior retained
}
