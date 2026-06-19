<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Admin panel lives under /admin, everything else is the mahasiswa portal.
        if ($request->is('admin') || $request->is('admin/*')) {
            return route('login');
        }

        return route('mahasiswa.login');
    }
}
