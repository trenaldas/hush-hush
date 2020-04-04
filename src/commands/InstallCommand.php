<?php

namespace trenaldas\HushHush\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

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
        $this->comment('Creating hush-hush.yml file in your root directory');
        file_put_contents(base_path() . '/hush-hush.yml', '');

        $this->comment('Publishing hush-hush.php config file.');
        $this->callSilent('vendor:publish', ['--tag' => 'hush-hush-config']);

        $this->comment('
  _   _           _           _   _           _
 | | | |_   _ ___| |__       | | | |_   _ ___| |__
 | |_| | | | / __| \'_ \ _____| |_| | | | / __| \'_ \
 |  _  | |_| \__ \ | | |_____|  _  | |_| \__ \ | | |
 |_| |_|\__,_|___/_| |_|     |_| |_|\__,_|___/_| |_|

                                                    ');
    }
}
