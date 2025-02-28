<?php

namespace App\Providers;

use App\Models\Topic;
use App\Repositories\v1\TopicRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TopicRepository::class, function ($app) {
            return new TopicRepository($app->make(Topic::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
