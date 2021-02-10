<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ComposerServiceProvider extends ServiceProvider
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
        view()->composer('app','App\Http\ViewComposers\CategoryComposer');
    }

    public function compose(View $view)
    {
        $settings = DB::table('cms_settings')->first();
        $view->with(['settings' => $settings]);
    }
}
