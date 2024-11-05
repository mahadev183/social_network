<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Auth::class, function ($app) {
            $firebaseConfigPath = base_path('fir-7224a-firebase-adminsdk-jyjc4-db7333dbc7.json');
            return (new Factory)
                ->withServiceAccount($firebaseConfigPath)
                ->createAuth();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Additional boot logic, if necessary
    }
}
