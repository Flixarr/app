<?php

namespace App\Services;

use App\Models\PlexServer;
use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

class PlexApi
{
    protected $clientId;
    protected $host;
    protected $port;
    protected $scheme;
    protected $token;
    protected $accessToken;
    protected $headers;

    public function __construct()
    {
        // Set the Client Identifier
        $this->clientId = config('app.name') . '-' . config('app.build');
        // $this->clientId = (string) Str::uuid();

        // Retreive the Plex server if one has been saved.
        $plexServer = PlexServer::where('id', Settings::get('plex_server_id', null))->first();

        // Get the local server details.
        $this->host = $plexServer['host'] ?? null;
        $this->port = $plexServer['port'] ?? null;
        $this->scheme = $plexServer['scheme'] ?? 'http';
        $this->accessToken = $plexServer['accessToken'] ?? null;

        // Plex.tv headers
        $this->headers = [
            'X-Plex-Device-Name' => (new Agent)->browser() . ' (' . config('app.name') . ')',
            'X-Plex-Version' => 'Plex OAuth',
            'X-Plex-Product' => config('app.name'),
            'X-Plex-Device' => (new Agent)->platform(),
            'X-Plex-Client-Identifier' => $this->clientId,
            'X-Plex-Platform' => '',
            'X-Plex-Platform-Version' => '',
            'X-Plex-Model' => '',
            'X-Plex-Layout' => '',
        ];
    }

    /**
     * Make local API calls to your plex server
     *
     * @param string $path
     * @param integer $timeout
     */
    function plexCall(string $path, array $params = [], int $timeout = 5)
    {
        // Build the URL to your local plex server
        $url = $this->scheme . '://' . $this->host . ':' . $this->port . $path;

        // Make the API call
        // $response = Http::timeout($timeout)->get($url, $this->headers);
        $response = Http::withHeaders($this->headers)->timeout($timeout)->get($url, $params);

        // Convert the XML response to an array
        $array = xml2array($response);

        // Return the response in an array
        return $array;
    }

    /**
     * Make external API calls to Plex.tv
     *
     * @param string $endpoint
     * @param array $params
     * @param string $type
     * @param boolean $isXml
     */
    function plexTvCall($endpoint, $params = [], $type = 'get', $isXml = false)
    {
        // Build the URL
        $url = 'https://plex.tv/api/v2' . $endpoint;

        // Make the API call
        try {
            $response = Http::withHeaders($this->headers)->$type($url, $params);
        } catch (\Exception $e) {
            report($e);

            dd($e);
        }

        // If the response is XML, convert the response to an array
        if ($isXml) {
            $response = xml2array($response->body());
        }

        // Return the response
        return $response;
    }

    /**
     * ===========================================================================
     * ============================= AUTHENTICATION ==============================
     * ===========================================================================
     */

    function generateAuthPin()
    {
        // Generate an Auth PIN
        $authPin = $this->plexTvCall('/pins', ['strong' => 'true'], 'post', true);

        // Store the PIN, set expiration to 30 mins
        session(['plex_auth_pin' => $authPin], 30);
    }


    /**
     * Returns the user authentication / login URL
     * 
     * This function generates a PIN for Flixarr, stores 
     * that PIN in the session, then returns the 
     * authentication / login URL.
     *
     * @return bool|string
     */
    function authUrl(): bool|string
    {
        // Generate an Auth PIN
        $this->generateAuthPin();

        $authPin = session('plex_auth_pin');

        if (!isset(session('plex_auth_pin')['code'])) {
            return false;
        }

        // Return the Auth URL with the ClientID and the Auth PIN Code
        return "https://app.plex.tv/auth#!?clientID=" . $this->clientId . "&code=" . $authPin['code'];
    }

    /**
     * Get the status of the User's Authentication during Plex signin/setup
     *
     * @return bool|array
     */
    function authStatus(): bool|array|string
    {
        // Throw an error if we do not have a Plex Auth PIN ID
        if (!isset(session('plex_auth_pin')['id'])) {
            return [
                'error' => 'issue with plex auth pin',
                'data' => session('plex_auth_pin'),
            ];
        }

        $response = $this->plexTvCall('/pins/' . session('plex_auth_pin')['id']);

        if ($response->status() === 429) {
            return [
                'error' => 'Too many requests',
                'data' => $response->body(),
            ];
        }

        // Convert the response from XML to an Array after we have checked the response status
        $response = xml2array($response->body());

        // Should return 'authToken' but it should be empty.
        // If it doesn't return 'authToken', something's broken.
        if (!array_key_exists('authToken', $response)) {
            return [
                'error' => 'AuthToken missing',
                'data' => $response,
            ];
        }


        if (array_key_exists('authToken', $response) && $response['authToken'] != '') {
            // Save token
            return [
                'status' => 'claimed',
            ];
        }

        // ⚠️ needs to setup a catch for api errors
        // If this gets hit, something happened with the api
        return false;
    }

    function updateAccessToken(): bool
    {
    }
    // function authAccessToken()
    // {
    //     // return $this->plexTvCall('/pins/' . session('plex_auth_pin')['id'], [], 'get', true);
    //     return $this->plexCall('/library/sections', ['accessToken' => 'v-F9T6pC-quPeLkeCKHF']);
    // }
}
