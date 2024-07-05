<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class PlexTv
{
    protected $client_id;
    protected $headers;

    function __construct()
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
     * @param string $endpoint
     * @param array $params
     * @param string $type
     * @return \Illuminate\Http\Client\Response|array
     */
    function call($endpoint, $params = [], $type = 'get', $isXml = true): \Illuminate\Http\Client\Response|array|string
    {
        // Build the URL
        $url = 'https://plex.tv' . $endpoint;
        // Make the API call
        $response = Http::withHeaders($this->headers)->$type($url, $params);
        // Check the response status
        if ($response->failed()) {
            return [
                'error' => 'There was an issue communicating with Plex\'s API. (' . $response->status() . ')',
                'data' => xml2array($response->body())
            ];
        }
        // If response is expected to be XML, convert it
        if ($isXml) {
            return xml2array($response->body());
        }

        // Return response if xml isn't expected
        return $response->body();
    }

    /**
     * This function retrieves what Plex calls an "Auth PIN." It's an array of
     * authentication data, such as device information, localtion data, etc.
     *
     * @return array
     */
    function generateAuthPin(): array
    {
        // Return the Auth PIN array
        $response = $this->call('/api/v2/pins', ['strong' => 'true'], 'post');
        // If there wasn't any errors, store the auth pin in the session
        if (!hasError($response)) {
            session(['plex_auth_pin' => $response]);
        }
        // Return the Auth PIN or Error
        return $response;
    }

    /**
     * Returns the user authentication / login URL
     *
     * This function generates a Plex Auth Pin, then returns the Auth URL the
     * user must use to authenticate.
     *
     * @return string|array
     */
    function authUrl(): string|array
    {
        // Return the auth URL
        return "https://app.plex.tv/auth#!?clientID=" . $this->client_id . "&code=" . session('plex_auth_pin')['code'];
    }

    /**
     * This function checks the Plex Auth PIN to see if it has a valid Auth Token.
     * Once the user successfully signs in, an Auth Token will be generated. Once
     * the token is generated, save the token.
     *
     * If auth was successful, this function will return "true"
     * If auth was unsuccessful, this function will return "false"
     * If there was an error, this function will return an array
     *
     * @return bool|array
     */
    function authenticate(): bool|array
    {
        // Retrieve the previously saved Auth PIN from Plex by sending Plex the Pin ID
        $response = $this->call('/api/v2/pins/' . session('plex_auth_pin')['id']);
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
     *
     * @return bool
     */
    function verifyAuth(): bool
    {
        return (hasError((new PlexTv)->call('/api/v2/ping'))) ? false : true;
    }

    /**
     * This will refresh the authentication token to prevent it from expiring
     *
     * @return void
     */
    function ping(): void
    {
        $this->call('/api/v2/ping');
    }

    /**
     * Return a list of servers
     *
     * TODO: return only secure connections if 'httpsRequired' is true
     *
     * @return array
     */
    function getServers(): array
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
     *
     * @return array
     */
    function getDevices(): array
    {
        $devices = $this->call('/devices.xml');
        return $devices['Device'];
    }
}
