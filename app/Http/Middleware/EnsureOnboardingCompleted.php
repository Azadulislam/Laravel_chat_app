<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->onboarding_completed) {
            if (!$request->routeIs('onboarding.index') && !$request->routeIs('onboarding.complete')) {
                return redirect()->route('onboarding.index');
            }
        }

        return $next($request);
    }
}
