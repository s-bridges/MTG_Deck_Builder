<?php

namespace App\Http\Middleware;

use Closure;

class IsEditor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->isEditor()){
            return $next($request);
        }
        return redirect('home');
    }
}
