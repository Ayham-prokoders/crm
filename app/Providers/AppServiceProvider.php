<?php

namespace App\Providers;

use Modules\Lms\Models\Category;
use App\Services\FormsSyncService;
use App\Observers\NotificationObserver;
use Illuminate\Support\ServiceProvider;
use App\Http\Helper\RegisterationRequestHelper;
use Illuminate\Notifications\DatabaseNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FormsSyncService::class, function ($app) {
            return new FormsSyncService();
        });
        
        $this->app->singleton(RegisterationRequestHelper::class, function ($app) {
            return new RegisterationRequestHelper($app->make(FormsSyncService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DatabaseNotification::observe(NotificationObserver::class);
        // Category::observe(CodeObserver::class);
    }
}
