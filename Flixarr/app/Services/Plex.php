<?php

namespace App\Services;

use App\Models\PlexServer;
use Illuminate\Support\Facades\Http;

class Plex
{
    public $protocol;
    public $address;
    public $port;
    public $access_token;

    public function __construct($server = [])
    {
        $this->protocol = $server['protocol'];
        $this->address = $server['address'];
        $this->port = $server['port'];
        $this->access_token = settings('plex_token');
    }

    /**
     * Make local API calls to your plex server
     *
     * @param string $path
     * @param integer $timeout
     * @return \Illuminate\Http\Client\Response|array
     */
    function call(string $path, array $params = [], int $timeout = 3): \Illuminate\Http\Client\Response|array
    {
        // Build the URL to your local plex server
        $url = $this->protocol . '://' . $this->address . ':' . $this->port . $path;

        // Make the API call
        try {
            $response = Http::withHeaders(['X-Plex-Token' => $this->access_token])->connectTimeout($timeout)->get($url, $params);
        } catch (\Throwable $th) {
            return [
                'error' => 'There was an issue communicating with your Plex Server. (timeout exceeded)',
                'data' => $th->getMessage(),
            ];
        }

        if ($response->failed()) {
            return [
                'error' => 'There was an issue communicating with your Plex Server. (' . $response->status() . ')',
                'data' => $response->body()
            ];
        }

        // Convert the XML response to an array
        return xml2array($response->body());

        // Return the response in an array
        // return $response->body();
    }
}
