<?php

namespace App\Providers;

use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Interfaces\IdeaRepositoryInterface;
use App\Interfaces\TopicRepositoryInterface;
use App\Repositories\IdeaCommentsRepository;
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

        $this->app->bind(
            IdeaCommentRepositoryInterface::class,
            IdeaCommentsRepository::class
        );

        $this->app->bind(
            IdeaRepositoryInterface::class,
            IdeaRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
