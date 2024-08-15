<?php

namespace App\Http\Pages\Setup;

use App\Rules\ValidHost;
use App\Services\Plex;
use App\Services\PlexTv;
use App\Traits\WithLivewireLogger;
use App\Traits\WithValidationToasts;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class PlexServers extends Component
{
    use WireToast, WithLivewireLogger, WithValidationToasts;

    // Loading state
    public bool $loading = true;
    public array $servers = [];
    public array $selected_connection = [];
    public array $custom_connection = [];

    public function rules()
    {
        return [
            'custom_connection.host' => ['required', new ValidHost],
            'custom_connection.port' => ['required', 'integer', 'between:1,65535'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'custom_connection.host' => 'Hostname / IP Address',
            'custom_connection.port' => 'Port',
        ];
    }

    #[Layout('layouts.app', ['title' => 'Plex Servers'])]
    public function render()
    {
        return view('pages.setup.plex-servers');
    }

    public function load(): void
    {
        // Prevent user from visiting this page without a plex auth token
        if (!settings('plex_token')) {
            $this->redirect(route('setup.plex-auth'), false);

            return;
        }

        // Build custom connection array
        $this->custom_connection = [
            'host' => '',
            'port' => '32400',
            'ssl' => false,
        ];

        // Load plex servers
        $this->loadPlexServers();

        // Update state
        $this->loading = false;
    }

    public function loadPlexServers(): void
    {
        // Dev data
        $response = [
            [
                'name' => 'Plex Server',
                'platform' => 'Windows',
                'device' => 'PC',
                'publicAddress' => '162.239.195.175',
                'connections' => [
                    [
                        'protocol' => 'http',
                        'address' => '192.168.1.3',
                        'port' => '32400',
                        'uri' => 'https://192-168-1-3.8e41b6cde605491abfc1d33cf2d2d2f6.plex.direct:32400',
                        'local' => '1',
                        'relay' => '0',
                        'IPv6' => '0',
                        'online' => '1',
                    ],
                    [
                        'protocol' => 'https',
                        'address' => 'plex.home.hershey.co',
                        'port' => '32400',
                        'uri' => 'https://plex.home.hershey.co:32400',
                        'local' => '0',
                        'relay' => '0',
                        'IPv6' => '0',
                        'online' => '0',
                    ],
                    [
                        'protocol' => 'http',
                        'address' => '162.239.195.175',
                        'port' => '32400',
                        'uri' => 'https://162-239-195-175.8e41b6cde605491abfc1d33cf2d2d2f6.plex.direct:32400',
                        'local' => '0',
                        'relay' => '0',
                        'IPv6' => '0',
                        'online' => '0',
                    ],
                    [
                        'protocol' => 'http',
                        'address' => '1050:0000:0000:0000:0005:0600:0000:0000:0005:0600:0000:0000:0005:0600:300c:326b',
                        'port' => '32400',
                        'uri' => 'https://162-239-195-175.8e41b6cde605491abfc1d33cf2d2d2f6.plex.direct:32400',
                        'local' => '0',
                        'relay' => '0',
                        'IPv6' => '1',
                        'online' => '0',
                    ],
                ],
            ], [
                'name' => 'Test Server',
                'platform' => 'Linux',
                'device' => 'DS218',
                'publicAddress' => '8.8.8.8',
                'connections' => [
                    [
                        'protocol' => 'http',
                        'address' => '192.168.1.4',
                        'port' => '32400',
                        'uri' => 'https://192-168-1-4.e40b11e2e4aa41b0beaf81143f37e81a.plex.direct:32400',
                        'local' => '1',
                        'relay' => '0',
                        'IPv6' => '0',
                        'online' => '1',
                    ],
                ],
            ],
        ];

        // Load plex servers
        // $response = (new PlexTv)->getServers();

        // Error catching
        if (hasError($response, showToast: true, toastTitle: 'Plex API Error')) {
            return;
        }

        // Update property
        $this->servers = $response;

        // Test connection(s)
        // foreach ($this->servers as $server_key => $server) {
        //     foreach ($server['connections'] as $connection_key => $connection) {
        //         $status = (new Plex($connection))->testConnection();
        //         $this->servers[$server_key]['connections'][$connection_key]['online'] = hasError($status) ? false : true;
        //     }
        // }
    }

    public function selectPlexConnection(int $server_key, int $connection_key): void
    {
        $connection = $this->servers[$server_key]['connections'][$connection_key];

        $this->selected_connection = [
            'protocol' => $connection['protocol'],
            'address' => $connection['address'],
            'port' => $connection['port'],
        ];

        if ($this->testConnection()) {
            // Disable page
            $this->loading = true;
            // Save Server
            $this->saveServer();
            // Redirect
            $this->redirect(route('setup.services'));
        }
    }

    public function submitCustomConnection(): void
    {
        // Validate
        $this->validate();

        // Build connection array
        $this->selected_connection = [
            'protocol' => (isset($this->custom_connection['ssl']) && $this->custom_connection['ssl']) ? 'https' : 'http',
            'address' => $this->custom_connection['host'],
            'port' => $this->custom_connection['port'],
        ];

        if ($this->testConnection()) {
            // Disable page
            $this->loading = true;

            // Save server
            if ($this->saveServer()) {
                // If save was successful, redirect to next step
                $this->redirect(route('setup.services'));
            }
        }
    }

    public function testConnection(): bool
    {
        // Test connection
        $status = (new Plex($this->selected_connection))->testConnection();

        // Check for error
        return (hasError($status, showToast: true)) ? false : true;
    }

    public function saveServer(): bool
    {
        $server = (new Plex($this->selected_connection))->saveServerFromConnection();

        return hasError($server, showToast: true) ? false : true;
    }

    public function resetPlexAuth(): void
    {
        // Clear plex token if we have one
        settings(['plex_token' => null]);
        $this->redirect(route('setup.plex-auth'), navigate: false);
    }
}
