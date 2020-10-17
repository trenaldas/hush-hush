<?php

namespace trenaldas\HushHush;

use Aws\Exception\AwsException;
use Aws\Exception\CredentialsException;
use Aws\SecretsManager\SecretsManagerClient;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Yaml\Yaml;

class HushHush
{
    /** @var string */
    public $hushHushYmlPath;

    /** @var SecretsManagerClient */
    private $client;

    /** @var bool */
    private $ymlFileExist;

    public function __construct()
    {
        $this->hushHushYmlPath = base_path() . '/hush-hush.yml';
        $this->ymlFileExist    = file_exists($this->hushHushYmlPath);

        $this->client = new SecretsManagerClient(
            [
                'version' => '2017-10-17',
                'region'  => env('AWS_DEFAULT_REGION', 'eu-west-1'),
            ]
        );
    }

    public function setDatabaseLoginDetails() : void
    {
        if ($this->testConnection() ||
            ! $this->ymlFileExist ||
            (! config('hush-hush.database_option.env_file') &&
                ! config('hush-hush.database_option.config'))
        ) {
            return;
        }

        $hushHushYml = Yaml::parseFile($this->hushHushYmlPath);
        if (
            isset($hushHushYml['database']['connection']) &&
            isset($hushHushYml['database']['environments'][App::environment()])
        ) {
            $secret = json_decode($this->openSecret($hushHushYml['database']['environments'][App::environment()]));

            if ($secret) {
                if (config('hush-hush.database_option.config')) {
                    $this->useConfig($secret, $hushHushYml);
                }

                if (config('hush-hush.database_option.env_file')) {
                    $this->useEnvFile($secret);
                }
            }
        }
    }

    /**
     * @return null|object
     */
    public function uncover(string $localSecretName)
    {
        if ($this->ymlFileExist) {
            $hushHushSecrets = Yaml::parseFile($this->hushHushYmlPath);
            if (isset($hushHushSecrets['secrets'][$localSecretName])) {
                $secret = $hushHushSecrets['secrets'][$localSecretName];

                return json_decode($this->openSecret($secret[App::environment()]));
            }
        }

        return null;
    }

    /**
     * @return false|mixed|string
     * @throws AwsException
     */
    private function openSecret(string $secretName)
    {
        try {
            $result = $this->client->getSecretValue(
                [
                    'SecretId' => $secretName,
                ]
            );
        } catch (CredentialsException|AwsException|ResourceNotFoundException $e) {
            Log::error('Aws throws exception: ' . $e->getMessage());

            return false;
        }

        // Decrypts secret using the associated KMS CMK.
        // Depending on whether the secret is a string or binary, one of these fields will be populated.
        if (isset($result['SecretString'])) {
            $secret = $result['SecretString'];
        } else {
            $secret = base64_decode($result['SecretBinary']);
        }

        return $secret;
    }

    private function useConfig($secret, $hushHushYml) : void
    {
        config(
            [
                'database.connections.' . $hushHushYml['database']['connection'] . '.username' => $secret->username,
                'database.connections.' . $hushHushYml['database']['connection'] . '.password' => $secret->password,
            ]
        );
    }

    private function useEnvFile($secret) : void
    {
        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            file_put_contents(
                $envPath,
                str_replace(
                    'DB_USERNAME=' . env('DB_USERNAME'),
                    'DB_USERNAME=' . $secret->username,
                    file_get_contents($envPath)
                )
            );
            file_put_contents(
                $envPath,
                str_replace(
                    'DB_PASSWORD=' . env('DB_PASSWORD'),
                    'DB_PASSWORD=' . $secret->password,
                    file_get_contents($envPath)
                )
            );

            Artisan::call('config:clear');
        }
    }

    private function testConnection() : bool
    {
        try {
            DB::connection()->getPdo();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
