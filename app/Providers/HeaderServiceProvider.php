<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\HeaderComposer;

class HeaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'layouts.app','App\Http\Composers\HeaderComposer'
        );
    }
}
