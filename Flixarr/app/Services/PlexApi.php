<?php

namespace App\Services;

use App\Models\PlexServer;
use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;
use Usernotnull\Toast\Concerns\WireToast;

class PlexApi
{
    use WireToast;

    protected $client_id;
    protected $host;
    protected $port;
    protected $scheme;
    // protected $token;
    protected $access_token;
    protected $headers;

    public function __construct()
    {
        // Set the Client Identifier
        $this->client_id = config('app.name');
        // $this->client_id = (string) Str::uuid();

        // Retreive the Plex server if one has been saved.
        $plexServer = PlexServer::where('id', Settings::get('plex_server_id', null))->first();

        // Get the local server details.
        $this->host = $plexServer['host'] ?? null;
        $this->port = $plexServer['port'] ?? null;
        $this->scheme = $plexServer['scheme'] ?? 'http';
        $this->access_token = settings('plex_token');
        // $this->access_token = $plexServer['access_token'] ?? null;

        // Plex.tv headers
        $this->headers = [

            'X-Plex-Token' => $this->access_token,
            'X-Plex-Device' => 'Docker ID: ' . gethostname(),
            'X-Plex-Device-Name' => 'Flixarr',
            'X-Plex-Version' => config('app.build'),
            'X-Plex-Product' => 'Flixarr',
            'X-Plex-Platform' => 'docker',
            'X-Plex-Provides' => 'controller',
            'X-Plex-Client-Identifier' => $this->client_id,



            // ⚠️ Do not delete the below data
            // 'X-Plex-Device-Name' => (new Agent)->browser() . ' (' . config('app.name') . ')',
            // 'X-Plex-Version' => 'Plex OAuth',
            // 'X-Plex-Product' => config('app.name'),
            // 'X-Plex-Device' => (new Agent)->platform(),
            // 'X-Plex-Client-Identifier' => $this->client_id,
            // 'X-Plex-Platform' => '',
            // 'X-Plex-Platform-Version' => '',
            // 'X-Plex-Model' => '',
            // 'X-Plex-Layout' => '',
        ];
    }

    /**
     * Make local API calls to your plex server
     *
     * @param string $path
     * @param integer $timeout
     * @return \Illuminate\Http\Client\Response|array
     */
    function plexCall(string $path, array $params = [], int $timeout = 5): \Illuminate\Http\Client\Response|array
    {
        // Build the URL to your local plex server
        $url = $this->scheme . '://' . $this->host . ':' . $this->port . $path;

        // Make the API call
        $response = Http::withHeaders($this->headers)->timeout($timeout)->get($url, $params);

        if ($response->failed()) {
            return [
                'error' => 'There was an issue communicating with your Plex Server. (' . $response->status() . ')',
                'data' => $response
            ];
        }

        // Convert the XML response to an array
        // $array = xml2array($response);

        // Return the response in an array
        return $response;
    }

    /**
     * Make external API calls to Plex.tv
     *
     * @param string $endpoint
     * @param array $params
     * @param string $type
     * @return \Illuminate\Http\Client\Response|array
     */
    function plexTvCall($endpoint, $params = [], $type = 'get'): \Illuminate\Http\Client\Response|array|string
    {
        // Build the URL
        $url = 'https://plex.tv' . $endpoint;

        // Make the API call
        // $response = Http::withHeaders($this->headers)->$type($url, $params);
        $response = Http::withHeaders($this->headers)->get($url, $params);

        // Check the response status
        if ($response->failed()) {
            return [
                'error' => 'There was an issue communicating with Plex\'s API. (' . $response->status() . ')',
                'data' => xml2array($response->body())
            ];
        }

        // Convert the response to an array and return it
        return xml2array($response->body());
    }

    /**
     * Plex Authentication PIN
     *
     * This function retrieves what Plex calls an "Auth PIN." It's an array of
     * authentication data, such as device information, localtion data, etc.
     *
     * Example of Plex's Auth PIN
     * [
     *  "id" => "000000000"
     *  "code" => "abcdefghijklmnopqrstuvwxyz"
     *  "product" => "Flixarr"
     *  "trusted" => "0"
     *  "qr" => "https://plex.tv/api/v2/pins/qr/abcdefghijklmnopqrstuvwxyz"
     *  "clientIdentifier" => "Flixarr-1.0.0"
     *  "expiresIn" => "1800" (SECONDS)
     *  "createdAt" => "2024-06-20 06:55:56 UTC"
     *  "expiresAt" => "2024-06-20 07:25:56 UTC"
     *  "authToken" => ""
     *  "newRegistration" => ""
     *  "location" => [
     *      "code" => "US"
     *      "european_union_member" => "0"
     *      "continent_code" => "NA"
     *      "country" => "United States"
     *      "city" => "Louisville"
     *      "time_zone" => "America/New_York"
     *      "postal_code" => "40229"
     *      "in_privacy_restricted_country" => "0"
     *      "subdivisions" => "Kentucky"
     *      "coordinates" => "38.2527, -85.7585"
     * ]
     *
     * @return array
     */
    function generateAuthPin(): array
    {
        // Return the Auth PIN array
        $response = $this->plexTvCall('/api/v2/pins', ['strong' => 'true'], 'post');

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
     * Authenticate
     *
     * This function checks the Plex Auth PIN to see if it has a valid Auth Token.
     * Once the user successfully signs in, an Auth Token will be generated. Once
     * The token is generated, we save the token.
     *
     * If auth was successful, this function will return "true"
     * If auth was unsuccessful, this function will return "false"
     * If there was an error, this function will return an array
     *
     * Error arrays are constructed like so:
     *
     * [
     *      'error' => '[error type]',
     *      'data' => '[response from plex.tv]'
     * ]
     *
     * @return bool|array
     */
    function authenticate(): bool|array
    {

        // First, we need to check if we already have an auth token.
        if (settings('plex_token')) {
            // We do have a plex auth token
        }

        // What about a plex auth PIN?
        if (session()->has('plex_auth_pin')) {
            // We do have a plex auth pin
        }

        // We are not authenticated whatsoever









        // Retrieve the previously saved Auth PIN from Plex by sending Plex the Pin ID
        $response = $this->plexTvCall('/api/v2/pins/' . session('plex_auth_pin')['id']);

        // If there was an error, return it
        if (hasError($response)) {
            return $response;
        }

        // I'm not sure if this is necessary, because I don't fully understand Plex's API
        // But if for any reason the response doesn't include the 'authToken' key,
        // we need to throw an error because that will break the code. I added this a while
        // back, not 100% sure why (I think it was before we checked the satus on the API call)
        // but I feel like I kept getting responses that would break the code because 'authToken'
        // was missing.
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

            // Test Authentication



            // Return true to confirm authentication
            return true;
        }

        return false;
    }

    function testAuthentication()
    {
        $test = (new PlexApi)->plexTvCall('/devices.xml');
        if (hasError($test)) {
            return [
                'error' => 'There was an issue while testing authentication. Refresh the page and try again.',
                'data' => $test,
            ];
        }

        return true;
    }
}
