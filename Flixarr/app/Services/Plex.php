<?php

namespace App\Services;

use App\Models\PlexServer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Usernotnull\Toast\Concerns\WireToast;

class Plex
{
    use WireToast;

    public $protocol;
    public $address;
    public $port;
    public $access_token;

    public function __construct($connection = [])
    {
        $this->protocol = $connection['protocol'];
        $this->address = $connection['address'];
        $this->port = $connection['port'];
        $this->access_token = settings('plex_token');
    }

    /**
     * Make local API calls to your plex server
     */
    public function call(string $path, array $params = [], int $timeout = 5): \Illuminate\Http\Client\Response|array
    {
        // Build the URL to your local plex server
        $url = $this->protocol . '://' . $this->address . ':' . $this->port . $path;

        // Log it
        // logAction('Plex API', 'Calling Server...', [
        //     'url' => $url,
        //     'path' => $path,
        //     'params' => $params,
        // ]);

        // Make the API call
        try {
            $response = Http::withHeaders(['X-Plex-Token' => $this->access_token])->connectTimeout($timeout)->get($url, $params);
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
                    'error' => 'There was an issue communicating with your Plex Server. (408)',
                    'data' => $error->getMessage(),
                ];
            }
        }

        // Don't really know if this is needed, but just in case
        if ($response->failed()) {
            return [
                'error' => 'There was an issue communicating with your Plex Server. (' . $response->status() . ')',
                'data' => $response->body(),
            ];
        }

        // Convert the XML response to an array
        return xml2array($response->body());
    }

    /**
     * Test Plex Server Connection
     */
    public function testConnection(): array|bool
    {
        $status = $this->call('/');

        if (hasError($status)) {
            return $status;
        }

        if (!array_key_exists('machineIdentifier', $status)) {
            return [
                'error' => 'There was an issue communicating with your Plex Server. (500)',
                'data' => $status,
            ];
        }

        return true;
    }

    /**
     * Returns the friendly server name
     */
    public function getServerName(): array|string
    {
        logAction('Plex API', 'getServerName');

        $response = $this->call('/');

        if (!array_key_exists('friendlyName', $response)) {
            return [
                'error' => 'There was an issue getting the server name.',
                'data' => $response,
            ];
        }

        return $response['friendlyName'];
    }

    /**
     * Saves the server details of the current connection
     *
     * @param  bool  $setAsActiveServer
     *
     * @return  PlexServer
     */
    public function saveServerFromConnection($setAsActiveServer = true): PlexServer|array
    {
        // Create new plex server model
        $server = new PlexServer;

        $server->name = $this->getServerName();
        $server->protocol = $this->protocol;
        $server->address = $this->address;
        $server->port = $this->port;

        try {
            $server->save();
        } catch (\Throwable $throwable) {
            logError('Plex API', 'saveServerFromConnection', $throwable);

            return [
                'error' => 'There was an issue saving the server to the database. Refresh the page and try again.',
                'data' => $throwable,
            ];
        }

        if ($setAsActiveServer) {
            settings(['plex_active_server' => $server->id]);
        }

        return $server;
    }
}
