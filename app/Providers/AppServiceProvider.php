<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('components.topbar', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $notificationService = app(\App\Services\NotificationService::class);
                $view->with('systemNotifications', $notificationService->getNotifications(\Illuminate\Support\Facades\Auth::user()));
            } else {
                $view->with('systemNotifications', [
                    'total_count'  => 0,
                    'urgent_count' => 0,
                    'items'        => collect(),
                ]);
            }
        });
    }
}
