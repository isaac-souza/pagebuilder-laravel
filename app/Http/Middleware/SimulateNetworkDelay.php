<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SimulateNetworkDelay
{
    /**
     * Simulate network delay
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if(config('app.env') != 'production')
        {
            // sleep(1);
        }

        return $next($request);
    }
}
