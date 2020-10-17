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
    public string $hushHushYmlPath;

    private SecretsManagerClient $client;

    private bool $ymlFileExist;

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
        if ($this->testConnection()  || ! $this->ymlFileExist) {
            return;
        }

        $hushHushYml = Yaml::parseFile($this->hushHushYmlPath);

        if (
            isset($hushHushYml['database']['connection']) &&
            isset($hushHushYml['database']['environments'][App::environment()])
        ) {
            $secret = json_decode($this->openSecret($hushHushYml['database']['environments'][App::environment()]));

            if ($secret) {
                $this->setSecretForDatabase($secret, $hushHushYml);
            }
        }
    }

    public function uncover(string $localSecretName): object
    {
        if (! $this->ymlFileExist) {
            throw new Exception('hush-hush.yml file not found!');
        }

        $hushHushSecrets = Yaml::parseFile($this->hushHushYmlPath);

        if (! isset($hushHushSecrets['secrets'][$localSecretName])) {
            throw new Exception('Secret was not found in hush-hush.yml file!');
        }

        $secret = $hushHushSecrets['secrets'][$localSecretName];

        return json_decode($this->openSecret($secret[App::environment()]));
    }

    private function openSecret(string $secretName)
    {
        try {
            $result = $this->client->getSecretValue(
                [
                    'SecretId' => $secretName,
                ]
            );
        } catch (Exception $e) {
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

    private function setSecretForDatabase(object $secret, array $hushHushYml) : void
    {
        config(
            [
                'database.connections.' . $hushHushYml['database']['connection'] . '.username' => $secret->username,
                'database.connections.' . $hushHushYml['database']['connection'] . '.password' => $secret->password,
            ]
        );
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
