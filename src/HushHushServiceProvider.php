<?php

namespace trenaldas\HushHush;

use Illuminate\Contracts\Container\BindingResolutionException as BindingResolutionExceptionAlias;
use Illuminate\Support\ServiceProvider;

class HushHushServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @throws BindingResolutionExceptionAlias
     */
    public function register()
    {
        $this->app->make('trenaldas\HushHush\HushHush');

        $this->commands(
            [
                InstallCommand::class,
            ]
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/hushhush.php', 'hush-hush'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/hushhush.php' => config_path('hushhush.php'),
            ],'hush-hush-config');

//        $this->publishes(
//            [
//                __DIR__ . '/HushHushServiceProvider.stub' => app_path(
//                    'Providers/HushHushServiceProvider.php'
//                ),
//            ],'hush-hush-provider');

//        $hushHush->setDatabaseLoginDetails();
    }
}
