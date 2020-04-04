<?php

namespace trenaldas\HushHush\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class SetDatabaseSecretCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hush-hush:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to set AWS Secret Manager secret for database connection';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $ymlContent = [];
        $connections = config('database.connections');
        $ymlContent['database']['connection']  = $this->choice('Select database connection driver:', array_keys($connections), 'mysql');

        $this->comment('Enter AWS secret name for different environments:');
        $environments = (config('hush-hush.environments'));

        foreach ($environments as $environment)
        {
            $ymlContent['database']['environments'][$environment] = $this->ask("{$environment}");
        }

        $ymlContent = Yaml::dump($ymlContent, 3);
        file_put_contents(base_path() . '/hush-hush.yml', $ymlContent);

        $this->comment('
  _   _           _           _   _           _
 | | | |_   _ ___| |__       | | | |_   _ ___| |__
 | |_| | | | / __| \'_ \ _____| |_| | | | / __| \'_ \
 |  _  | |_| \__ \ | | |_____|  _  | |_| \__ \ | | |
 |_| |_|\__,_|___/_| |_|     |_| |_|\__,_|___/_| |_|

                                                    ');
        $this->callSilent('vendor:publish', ['--tag' => 'hush-hush-config']);
    }
}
