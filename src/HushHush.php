<?php

namespace trenaldas\HushHush;

use Aws\Exception\AwsException;
use Aws\SecretsManager\SecretsManagerClient;
use Illuminate\Support\Facades\App;
use Symfony\Component\Yaml\Yaml;

class HushHush
{
    /** @var string */
    public $hushHushYmlPath;

    /** @var SecretsManagerClient */
    private $client;

    /** @var bool */
    private $ymlFileExist = false;

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

    /**
     * @return void
     */
    public function setDatabaseLoginDetails()
    {
        if ($this->ymlFileExist) {
            $hushHushYml = Yaml::parseFile($this->hushHushYmlPath);
            if (isset($hushHushYml['database']['connection']) &&
                isset($hushHushYml['database']['environments'][App::environment()])) {
                $secret = json_decode($this->openSecret($hushHushYml['database']['environments'][App::environment()]));
                config(
                    [
                        'database.connections.' . $hushHushYml['database']['connection'] . '.username' => $secret->username,
                        'database.connections.' . $hushHushYml['database']['connection'] . '.password' => $secret->password,
                    ]
                );
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
     * @throws AwsException
     *
     * @return false|mixed|string
     */
    private function openSecret(string $secretName)
    {
        try {
            $result = $this->client->getSecretValue(
                [
                    'SecretId' => $secretName,
                ]
            );

        } catch (AwsException $e) {
            $error = $e->getAwsErrorCode();
            if ($error == 'DecryptionFailureException') {
                // Secrets Manager can't decrypt the protected secret text using the provided AWS KMS key.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InternalServiceErrorException') {
                // An error occurred on the server side.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InvalidParameterException') {
                // You provided an invalid value for a parameter.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InvalidRequestException') {
                // You provided a parameter value that is not valid for the current state of the resource.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'ResourceNotFoundException') {
                // We can't find the resource that you asked for.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
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
}
