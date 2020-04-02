<?php

namespace trenaldas\HushHush;

use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;
use Aws\SecretsManager\SecretsManagerClient;

class HushHush
{
    /** @var SecretsManagerClient */
    private $client;

    public function __construct()
    {
        $clientConfig =
            [
                'version' => '2017-10-17',
                'region'  => env('AWS_REGION', 'eu-west-1'),
            ];

        if (config('hushhush.credentials')) {
            $clientConfig['credentials'] = new Credentials(
                env('AWS_ACCESS_KEY_ID'),
                env('AWS_SECRET_ACCESS_KEY')
            );
        }

        $this->client = new SecretsManagerClient($clientConfig);
    }

    public function openSecret(string $secretName)
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

    public function setDatabaseLoginDetails()
    {
        if (config('hushhush.database.connection') && config('hushhush.database.secret')) {
            $secret = json_decode($this->openSecret(config('hushhush.database.secret')));
            config(
                [
                    'database.connections.mysql.username' => $secret->username,
                    'database.connections.mysql.password' => $secret->password,
                ]
            );
        }
    }
}
