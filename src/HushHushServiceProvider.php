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
                SetDatabaseSecretCommand::class,
                CreateSecretCommand::class,
            ]
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
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
