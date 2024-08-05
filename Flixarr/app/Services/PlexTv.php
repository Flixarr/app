<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Usernotnull\Toast\Concerns\WireToast;

class PlexTv
{
    use WireToast;

    protected $client_id;
    protected $headers;

    public function __construct()
    {
        $this->client_id = config('app.name');
        $this->headers = [
            'X-Plex-Token' => settings('plex_token'),
            'X-Plex-Device' => 'Docker ID: ' . gethostname(),
            'X-Plex-Device-Name' => 'Flixarr',
            'X-Plex-Version' => config('app.build'),
            'X-Plex-Product' => 'Flixarr',
            'X-Plex-Platform' => 'docker',
            'X-Plex-Provides' => 'controller',
            'X-Plex-Client-Identifier' => $this->client_id,
            // 'X-Plex-Platform-Version' => '',
            // 'X-Plex-Model' => '',
            // 'X-Plex-Layout' => '',
        ];
    }

    /**
     * Makes API calls to Plex.tv
     *
     * @param  string  $endpoint
     * @param  array   $params
     * @param  string  $type
     * @param  mixed   $isXml
     *
     * @return  array|\Illuminate\Http\Client\Response
     */
    public function call($endpoint, $params = [], $type = 'get', $isXml = true): \Illuminate\Http\Client\Response|array|string
    {
        // Build the URL
        $url = 'https://plex.tv' . $endpoint;

        // Make the API call
        try {
            // code...
            $response = Http::withHeaders($this->headers)->$type($url, $params);
        } catch (\Throwable $error) {
            return [
                'error' => 'There was an issue communicating with Plex.tv',
                'data' => $error->getMessage(),
            ];
        }

        // Check the response status
        if ($response->failed()) {
            return [
                'error' => 'There was an issue communicating with Plex\'s API. (' . $response->status() . ')',
                'data' => xml2array($response->body()),
            ];
        }

        // Convert response from XML to array if reqested
        $response = $isXml ? xml2array($response->body()) : $response->body;

        // Return response
        return $response;
    }

    /**
     * This function retrieves what Plex calls an "Auth PIN." It's an array of
     * authentication data, such as device information, localtion data, etc.
     */
    public function generateAuthPin(): array
    {
        // Return the Auth PIN array
        $response = $this->call('/api/v2/pins', ['strong' => 'true'], 'post');

        // If there wasn't any errors, store the auth pin in the session
        if (!hasError($response)) {
            settings(['plex_pin_id' => $response['id']]);
            settings(['plex_pin_code' => $response['code']]);
        }

        // Return the Auth PIN or Error
        return $response;
    }

    /**
     * Returns the user authentication / login URL
     *
     * This function generates a Plex Auth Pin, then returns the Auth URL the
     * user must use to authenticate.
     */
    public function authUrl(): array|string
    {
        // Return the auth URL
        return 'https://app.plex.tv/auth#!?clientID=' . $this->client_id . '&code=' . settings('plex_pin_code');
    }

    /**
     * This function checks the Plex Auth PIN to see if it has a valid Auth Token.
     * Once the user successfully signs in, an Auth Token will be generated. Once
     * the token is generated, save the token.
     *
     * If auth was successful, this function will return "true"
     * If auth was unsuccessful, this function will return "false"
     * If there was an error, this function will return an array
     */
    public function authenticate(): array|bool
    {
        // Retrieve the previously saved Auth PIN from Plex by sending Plex the Pin ID
        $response = $this->call('/api/v2/pins/' . settings('plex_pin_id'));

        // If there was an error, return it
        if (hasError($response)) {
            return $response;
        }

        // Ensure 'authToken' key exists in array
        if (!array_key_exists('authToken', $response)) {
            return [
                'error' => 'There was an issue with Plex\'s API. Refresh the page and try again. (Missing Authentication Token)',
                'data' => $response,
            ];
        }

        // If the auth token array key exists and it is not null, auth was successful.
        if ($response['authToken']) {
            // Save the token
            settings(['plex_token' => $response['authToken']]);

            // Success
            return true;
        }

        return false;
    }

    /**
     * Verify that we are authenticated with Plex.tv
     */
    public function verifyAuth(): bool
    {
        return (hasError((new PlexTv)->call('/api/v2/ping'))) ? false : true;
    }

    /**
     * This will refresh the authentication token to prevent it from expiring
     */
    public function ping(): void
    {
        $this->call('/api/v2/ping');
    }

    /**
     * Return a list of servers
     *
     * TODO: return only secure connections if 'httpsRequired' is true
     */
    public function getServers(): array
    {
        // Get user's resources (servers, clients, and remotes) from plex.tv
        $response = $this->call('/api/v2/resources', ['includeHttps' => '0']);

        if (hasError($response)) {
            return $response;
        }

        // Check if user has any servers associated with their account (owned or shared)
        if (!array_key_exists('resource', $response)) {
            return [];
        }

        // Pluck servers only
        $response = collect($response['resource'])->where('owned', true)->where('provides', 'server')->toArray();

        // Check if user has any owned servers
        if (!array_key_exists(0, $response)) {
            return [];
        }

        // Check if user has mutliple servers
        if (array_key_exists(0, $response)) {
            // User has multiple servers
            foreach ($response as $server) {
                $servers[] = $server;
            }
        } else {
            // User has only one server
            $servers[] = $response['resource'];
        }

        // Format server connections
        foreach ($servers as $key => $server) {
            $connections = $server['connections']['connection'];
            $servers[$key]['connections'] = (array_key_exists(0, $connections)) ? $connections : [$connections];
        }

        return $servers;
    }

    /**
     * Return the user's devices
     */
    public function getDevices(): array
    {
        $devices = $this->call('/devices.xml');

        return $devices['Device'];
    }
}
