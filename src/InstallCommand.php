<?php

namespace trenaldas\HushHush;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hush-hush:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs and publishes all of the Hush-Hush resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
//        $this->comment('Publishing Hush-Hush Service Provider...');
////        $this->callSilent('vendor:publish', ['--tag' => 'hush-hush-provider']);
//
        $this->comment('Publishing Hush-Hush Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'hush-hush-config']);

//        $this->registerHushHushServiceProvider();
    }

    /**
     * Register Hush-Hush inside app.php providers list
     *
     * @return void
     */
    protected function registerHushHushServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\HushHushServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\HushHushServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/HushHushServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/HushHushServiceProvider.php'))
        ));
    }
}
