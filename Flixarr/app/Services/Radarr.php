<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Str;

class Radarr
{
    public string $protocol;
    public string $host;
    public int $port;
    public string $key;

    public function __construct($connection = [])
    {
        if (!empty($connection)) {
            // Use custom connection (mainly for setup)
            $this->protocol = $connection['ssl'] ? 'https' : 'http';
            $this->host = $connection['host'];
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
        $url = $this->protocol . '://' . $this->host . ':' . $this->port . $path;

        // Make the API call
        try {
            $response = Http::withHeaders(['X-Api-Key' => $this->key])->connectTimeout($timeout)->get($url, $params);
        } catch (\Throwable $error) {
            // Check if 408 (timeout) error
            if (Str::contains($error->getMessage(), ['SSL', 'certificate'])) {
                return [
                    'error' => 'There was an issue with the SSL certificate. Disable SSL and try again.',
                    // 'error' => 'There was an issue with the SSL certificate. Try to disable "Use SSL" and try again.',
                    'data' => $error->getMessage(),
                ];
            } else {
                return [
                    'error' => 'There was an issue communicating with Radarr. Please report this error. (408)',
                    'data' => $error->getMessage(),
                ];
            }
        }

        if ($response->failed()) {
            return [
                'error' => [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'object' => $response->object(),
                ],
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
        $radarr = Service::create([
            'name' => 'Radarr',
        ]);
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

        // If there was a call error, return the error
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
