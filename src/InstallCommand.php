<?php

namespace trenaldas\HushHush;

use Illuminate\Console\Command;
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
    protected $description = 'Installs and publishes Hush-Hush resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! file_exists(base_path() . '/hush-hush.yml')) {
            $this->comment('Creating hush-hush.yml file in your root directory');
            file_put_contents(base_path() . '/hush-hush.yml', '');
        }

        $this->comment('Publishing hush-hush.php config file.');
        $this->callSilent('vendor:publish', ['--tag' => 'hush-hush-config']);

        $this->comment('----------------------------------------------------');
        $this->comment('- Check config/hush-hush.php file for environments -');
        $this->comment('----------------------------------------------------');

        $this->comment('
  _   _           _           _   _           _
 | | | |_   _ ___| |__       | | | |_   _ ___| |__
 | |_| | | | / __| \'_ \ _____| |_| | | | / __| \'_ \
 |  _  | |_| \__ \ | | |_____|  _  | |_| \__ \ | | |
 |_| |_|\__,_|___/_| |_|     |_| |_|\__,_|___/_| |_|

                                                    ');
    }
}
