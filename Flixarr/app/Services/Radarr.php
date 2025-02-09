<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Str;

use function PHPSTORM_META\map;

class Radarr
{
    public string $protocol;
    public string $address;
    public int $port;
    public string $key;

    public function __construct($connection = [])
    {
        if (!empty($connection)) {
            // Use custom connection (mainly for setup)
            $this->protocol = $connection['ssl'] ? 'https' : 'http';
            $this->address = $connection['address'];
            $this->port = $connection['port'];
            $this->key = $connection['key'];
        } else {
            // Grab default Radarr server
        }
    }

    /**
     * Make local API calls to your plex server
     */
    public function call(string $path, array $params = [], int $timeout = 5): \Illuminate\Http\Client\Response|array|string
    {
        // Build the URL to your local plex server
        $url = $this->protocol . '://' . $this->address . ':' . $this->port . $path;

        // Make the API call
        try {
            $response = Http::withHeaders(['X-Api-Key' => $this->key])->connectTimeout($timeout)->get($url, $params);
        } catch (\Throwable $error) {
            $error_message = $error->getMessage();
            $error_data = [
                'path' => $path,
                'params' => $params,
                'location' => [
                    'file' => __FILE__,
                    'namespace' => __NAMESPACE__,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'trait' => __TRAIT__,
                    'function' => __FUNCTION__,
                    'line' => __LINE__,
                ],
                'getMessage' => $error->getMessage(),
                'getCode' => $error->getCode(),
            ];

            // Check if 408 (timeout) error
            if (Str::contains($error_message, ['Connection refused', 'Connection timeout'])) {
                return [
                    'error' => 'There was an issue communicating with your Radarr service. Double-check the connection details and try again.',
                    'data' => $error_data,
                ];
            } else if (Str::contains($error_message, ['SSL', 'certificate'])) {
                return [
                    'error' => 'There was an issue with the SSL certificate. Make sure you are using the correct port number. Usually, when using SSL with a hostname/domain, port 443 is used.',
                    'data' => $error_data
                ];
            } else {
                return [
                    'error' => 'There was an unknown error communicating with Radarr. Please report this error via GitHub issues.',
                    'data' => $error_data,
                ];
            }
        }

        if ($response->failed()) {
            $error_data = [
                'path' => $path,
                'params' => $params,
                'location' => [
                    'file' => __FILE__,
                    'namespace' => __NAMESPACE__,
                    'class' => __CLASS__,
                    'method' => __METHOD__,
                    'trait' => __TRAIT__,
                    'function' => __FUNCTION__,
                    'line' => __LINE__,
                ],
                'status' => $response->status() ?? '',
                'body' => $response->body() ?? '',
                'object' => $response->object() ?? '',
            ];

            if ($response->status() == 401) {
                return [
                    'error' => 'Invalid API Key',
                    'data' => $error_data,
                ];
            }

            return [
                'error' => 'There was an unknown error communicating with Radarr. Please report this error via GitHub issues.',
                'data' => $error_data,
            ];
        }

        return $response->json();
    }

    /**
     * Initialize the connection with Radarr
     *
     * This function runs when the user is adding their Radarr service.
     * It will test the connection, check the Radarr version, and save the service.
     */
    public function initConnection(): array
    {
        // Get the system status of Radarr
        $connectionStatus = $this->testConnection();

        // If the connection test returned an error, forward that error
        if (hasError($connectionStatus)) {
            return $connectionStatus;
        }

        /** TODO */
        // Check Radarr version number
        // Ensure Radarr version is greater than or equal to the current version

        // Save service
        // If a service with the same API Key is saved, update instead of create
        $service = Service::where('key', $this->key)->firstOrNew();
        $service->name = 'Radarr';
        $service->protocol = $this->protocol;
        $service->address = $this->address;
        $service->port = $this->port;
        $service->key = $this->key;
        $service->save();

        return $service->toArray();
    }

    /**
     * Test Radarr Connection
     *
     * This function tests the Radarr connections by checking the system
     * status, which should return an array with a key of 'appName' and
     * a value of 'Radarr'. Simple as that.
     */
    public function testConnection(): array|bool
    {
        $response = $this->call('/api/v3/system/status');

        // If there was a call error, forward the error
        if (hasError($response)) {
            return $response;
        }

        // Check if response has a key of 'appName' with the value of 'Radarr'
        if (isset($response['appName']) && $response['appName'] === 'Radarr') {
            return true;
        } else {
            return [
                'error' => 'Invalid response from Radarr.',
                'data' => $response,
            ];
        }
    }
}
