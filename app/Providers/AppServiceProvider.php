<?php

namespace App\Providers;

use App\Interfaces\DepartmentRepositoryInterface;
use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\IdeaRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\TopicRepositoryInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\IdeaCommentsRepository;
use App\Repositories\IdeaRepository;
use App\Repositories\TopicRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );

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

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            TagRepositoryInterface::class,
            TagRepository::class
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
