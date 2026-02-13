<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Place, Event, Post, User};
use App\Policies\{PlacePolicy, EventPolicy, PostPolicy};

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
        // Register policies
        Gate::policy(Place::class, PlacePolicy::class);
        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);

        // Super admin bypass - super admins can do anything
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register NoCaptcha Alias safely
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('NoCaptcha', \Anhskohbo\NoCaptcha\Facades\NoCaptcha::class);
    }
}
