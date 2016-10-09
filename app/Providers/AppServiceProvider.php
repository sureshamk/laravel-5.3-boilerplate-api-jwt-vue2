<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Authentication\AuthenticationContract::class,
            \App\Repositories\Authentication\EloquentAuthenticationRepository::class
        );

        $this->app->bind(
            \App\Repositories\User\UserContract::class,
            \App\Repositories\User\EloquentUserRepository::class
        );



        $this->app->bind(
            \App\Repositories\Role\RoleRepositoryContract::class,
            \App\Repositories\Role\EloquentRoleRepository::class
        );

        $this->app->bind(
            \App\Repositories\Permission\PermissionRepositoryContract::class,
            \App\Repositories\Permission\EloquentPermissionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Permission\Group\PermissionGroupRepositoryContract::class,
            \App\Repositories\Permission\Group\EloquentPermissionGroupRepository::class
        );

        $this->app->bind(
            \App\Repositories\Permission\Dependency\PermissionDependencyRepositoryContract::class,
            \App\Repositories\Permission\Dependency\EloquentPermissionDependencyRepository::class
        );
    }
}
