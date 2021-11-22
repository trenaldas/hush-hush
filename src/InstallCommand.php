<?php

namespace trenaldas\HushHush;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class InstallCommand extends Command
{
    /** @var string */
    protected $signature = 'hush-hush:install';

    /** @var string */
    protected $description = 'Installs and publishes Hush-Hush resources';

    public function handle(): void
    {
        if (! file_exists(base_path() . '/hush-hush.yml')) {
            $this->comment('Creating hush-hush.yml file in your root directory');
            file_put_contents(base_path() . '/hush-hush.yml', '');
        }

        $this->comment('Publishing hush-hush.php config file.');
        $this->callSilent('vendor:publish', ['--tag' => 'hush-hush-config']);

        $this->comment('----------------------------------------------------');
        $this->comment('| Check config/hush-hush.php file                  |');
        $this->comment('| Use the following commands to set secrets        |');
        $this->comment('| php artisan hush-hush:database                   |');
        $this->comment('| php artisan hush-hush:create-secret              |');
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
