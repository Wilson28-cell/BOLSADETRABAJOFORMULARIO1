<?php

namespace App\Providers;

use App\Domain\Repositories\DatabaseUserRepository;
use App\Domain\Repositories\UserRepositoryInterface;
use App\UseCases\Common\FileUploadInterface;
use App\UseCases\Common\FileUploadService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileUploadInterface::class, FileUploadService::class);
        $this->app->bind(UserRepositoryInterface::class, DatabaseUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
