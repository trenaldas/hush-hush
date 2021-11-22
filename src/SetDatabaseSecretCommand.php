<?php

namespace trenaldas\HushHush;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class SetDatabaseSecretCommand extends Command
{
    /** @var string */
    protected $signature = 'hush-hush:database';

    /** @var string */
    protected $description = 'Command to set AWS Secret Manager secret for database connection';

    public function handle(): void
    {
        if (! file_exists(base_path() . '/hush-hush.yml')) {
            $this->comment('File hush-hush.yml does not exist. Run command php artisan hush-hush:install');
            return;
        }

        $ymlContent  = Yaml::parseFile(base_path() . '/hush-hush.yml');
        $connections = config('database.connections');
        $ymlContent['database']['connection']  = $this->choice('Select database connection:', array_keys($connections), 'mysql');

        $this->comment('Enter AWS secret name for different environments:');
        $environments = (config('hush-hush.environments'));

        foreach ($environments as $environment)
        {
            $ymlContent['database']['environments'][$environment] = $this->ask("{$environment}");
        }

        file_put_contents(
            base_path() . '/hush-hush.yml',
            Yaml::dump($ymlContent, 3)
        );
    }
}
