<?php

namespace Trenaldas\HushHush;

use Illuminate\Support\ServiceProvider;

class HushHushServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->make('Trenaldas\HushHush\HushHush');

        $this->commands([
            InstallCommand::class,
            SetDatabaseSecretCommand::class,
            CreateSecretCommand::class,
        ]);
    }

    public function boot(HushHush $hushHush): void
    {
        $this->publishes([
            __DIR__.'/../config/hush-hush.php' => config_path('hush-hush.php'),
        ], 'hush-hush-config');

        $hushHush->setDatabaseLoginDetails();
    }
}
