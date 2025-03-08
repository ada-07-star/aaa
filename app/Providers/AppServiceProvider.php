<?php

namespace App\Providers;

use App\Interfaces\TopicRepositoryInterface;
use App\Repositories\IdeaRepository;
use App\Repositories\TopicRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {


        $this->app->bind(
            TopicRepositoryInterface::class,
            TopicRepository::class
        );

        $this->app->bind(IdeaRepository::class, function ($app) {
            return new IdeaRepository();
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
