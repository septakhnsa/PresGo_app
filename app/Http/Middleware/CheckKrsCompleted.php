<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckKrsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only restrict for 'mahasiswa' role. 
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            if (Auth::user()->krs_completed == 0) {
                return redirect()->route('mahasiswa.krs');
            }
        }

        return $next($request);
    }
}
