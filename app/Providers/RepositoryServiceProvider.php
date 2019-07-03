<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\Eloquent\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\ImageRepository::class, \App\Repositories\Eloquent\ImageRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, \App\Repositories\Eloquent\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\TeamRepository::class, \App\Repositories\Eloquent\TeamRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\PositionRepository::class, \App\Repositories\Eloquent\PositionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\TypeRepository::class, \App\Repositories\Eloquent\TypeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\RegistrationRepository::class, \App\Repositories\Eloquent\RegistrationRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\TimeAbsenceRepository::class, \App\Repositories\Eloquent\TimeAbsenceRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\ApproverRepository::class, \App\Repositories\Eloquent\ApproverRepositoryEloquent::class);
        //:end-bindings:
    }
}
