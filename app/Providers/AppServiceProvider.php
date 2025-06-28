<?php

namespace App\Providers;

use App\Interfaces\DepartmentRepositoryInterface;
use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EvaluationRepositoryInterface;
use App\Interfaces\IdeaRatingRepositoryInterface;
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
use App\Interfaces\IdeaUserRepositoryInterface;
use App\Repositories\EvaluationRepository;
use App\Repositories\IdeaRatingRepository;
use App\Repositories\IdeaUserRepository;
use App\Interfaces\ObjectRepositoryInterface;
use App\Repositories\ObjectRepository;
use App\Interfaces\EvaluationObjectRepositoryInterface;
use App\Interfaces\TopicCategoryRepositoryInterface;
use App\Repositories\EvaluationObjectRepository;
use App\Interfaces\IdeaLogsRepositoryInterface;
use App\Interfaces\TopicTagRepositoryInterface;
use App\Repositories\TopicCategoryRepository;
use App\Repositories\IdeaLogsRepository;
use App\Repositories\TopicTagRepository;
use App\Observers\IdeaObserver;
use App\Models\Idea;

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

        $this->app->bind(
            IdeaRepositoryInterface::class,
            IdeaRepository::class
        );

        $this->app->bind(
            IdeaUserRepositoryInterface::class,
            IdeaUserRepository::class
        );

        $this->app->bind(
            IdeaRatingRepositoryInterface::class,
            IdeaRatingRepository::class
        );

        $this->app->bind(
            EvaluationRepositoryInterface::class,
            EvaluationRepository::class
        );

        $this->app->bind(
            ObjectRepositoryInterface::class,
            ObjectRepository::class
        );

        $this->app->bind(
            EvaluationObjectRepositoryInterface::class,
            EvaluationObjectRepository::class
        );

        $this->app->bind(
            IdeaLogsRepositoryInterface::class,
            IdeaLogsRepository::class
        );

        $this->app->bind(
            TopicTagRepositoryInterface::class,
            TopicTagRepository::class
        );

        $this->app->bind(
            TopicCategoryRepositoryInterface::class,
            TopicCategoryRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Idea::observe(IdeaObserver::class);
    }
}
