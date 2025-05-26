<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //分页样式由laravel8的tailwind css改为bootstrap UI
        Paginator::useBootstrap();
        //mysql版本低索引过长问题
        Schema::defaultStringLength(191);
    }
}
