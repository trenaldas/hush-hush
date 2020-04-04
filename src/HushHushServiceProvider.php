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
                Commands\InstallCommand::class,
                Commands\SetDatabaseSecretCommand::class,
                Commands\CreateSecretCommand::class,
            ]
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/hush-hush.php', 'hush-hush'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     *
     * @param HushHush $hushHush
     */
    public function boot(HushHush $hushHush)
    {
        $this->publishes(
            [
                __DIR__ . '/../config/hush-hush.php' => config_path('hush-hush.php'),
            ],'hush-hush-config');

        $hushHush->setDatabaseLoginDetails();
    }
}
