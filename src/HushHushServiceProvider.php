<?php

namespace trenaldas\HushHush;

use Illuminate\Contracts\Container\BindingResolutionException as BindingResolutionExceptionAlias;
use Illuminate\Support\ServiceProvider;
use trenaldas\HushHush\Commands\CreateSecretCommand;
use trenaldas\HushHush\Commands\SetDatabaseSecretCommand;
use trenaldas\HushHush\Commands\InstallCommand;

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
