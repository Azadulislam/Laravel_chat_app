<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\User;
use App\Observers\GroupObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Group::observe(GroupObserver::class);


        RateLimiter::for('chat-message', function (Request $request) {
        return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip())->response(function () {
            return response()->json([
                'message' => 'Too many messages. Please wait 10 seconds.'
            ], 429);

        });
    });
    }
}
