<?php

namespace Trenaldas\HushHush;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class CreateSecretCommand extends Command
{
    /** @var string */
    protected $signature = 'hush-hush:create-secret';

    /** @var string */
    protected $description = 'Create secret to use in your application';

    public function handle(): void
    {
        if (! file_exists(base_path() . '/hush-hush.yml')) {
            $this->comment('File hush-hush.yml does not exist. Run command php artisan hush-hush:install');
            return;
        }

        $hushHushYml  = Yaml::parseFile(base_path() . '/hush-hush.yml');
        $environments = (config('hush-hush.environments'));
        $secretName   = $this->ask('Enter local name for your secret');

        $this->comment('Enter AWS secret name for different environments:');
        foreach ($environments as $environment) {
            $hushHushYml['secrets'][$secretName][$environment] = $this->ask($environment);
        }

        file_put_contents(
            base_path() . '/hush-hush.yml',
            Yaml::dump($hushHushYml, 3)
        );
    }
}
