<?php

namespace trenaldas\HushHush\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class CreateSecretCommandCommand extends Command
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
    public function handle()
    {
        $hushHushYml  = Yaml::parseFile(base_path() . '/hush-hush.yml');
        $secretName   = $this->ask('Enter local name for your secret');
        $environments = (config('hush-hush.environments'));

        $this->comment('Enter AWS secret name for different environments:');
        foreach ($environments as $environment)
        {
            $hushHushYml['secrets'][$secretName][$environment] = $this->ask("{$environment}");
        }

        $hushHushYml = Yaml::dump($hushHushYml, 3);
        file_put_contents(base_path() . '/hush-hush.yml', $hushHushYml);
    }
}