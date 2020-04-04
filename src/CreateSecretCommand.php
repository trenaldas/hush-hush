<?php

namespace trenaldas\HushHush;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class CreateSecretCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hush-hush:create-secret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create secret to use in your application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(HushHush $hushHush)
    {
        if (! file_exists($hushHush->hushHushYmlPath)) {
            $this->comment('File hush-hush.yml does not exist. Run command php artisan hush-hush:install');
            return;
        }

        $hushHushYml  = Yaml::parseFile($hushHush->hushHushYmlPath);
        $environments = (config('hush-hush.environments'));
        $secretName   = $this->ask('Enter local name for your secret');

        $this->comment('Enter AWS secret name for different environments:');
        foreach ($environments as $environment)
        {
            $hushHushYml['secrets'][$secretName][$environment] = $this->ask("{$environment}");
        }

        $hushHushYml = Yaml::dump($hushHushYml, 3);
        file_put_contents($hushHush->hushHushYmlPath, $hushHushYml);
    }
}
