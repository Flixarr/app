<?php

namespace App\Http\Pages\Setup;

use App\Services\Plex;
use App\Services\PlexTv;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class PlexServers extends Component
{
    use WireToast;

    public $servers = [];
    public $servers_loaded = false;

    #[Layout('layouts.minimal', ['title' => 'Plex Authentication'])]
    public function render()
    {
        return view('pages.setup.plex-servers');
    }

    function load(): void
    {
        $response = [
            [
                'name' => 'Plex Server',
                'platform' => 'Windows',
                'device' => 'PC',
                'publicAddress' => '162.239.195.175',
                'connections' => [
                    [
                        "protocol" => "http",
                        "address" => "192.168.1.3",
                        "port" => "32400",
                        "uri" => "https://192-168-1-3.8e41b6cde605491abfc1d33cf2d2d2f6.plex.direct:32400",
                        "local" => "1",
                        "relay" => "0",
                        "IPv6" => "0",
                        'online' => "1",
                    ],
                    [
                        "protocol" => "https",
                        "address" => "plex.home.hershey.co",
                        "port" => "32400",
                        "uri" => "https://plex.home.hershey.co:32400",
                        "local" => "0",
                        "relay" => "0",
                        "IPv6" => "0",
                        'online' => "0",
                    ],
                    [
                        "protocol" => "http",
                        "address" => "162.239.195.175",
                        "port" => "32400",
                        "uri" => "https://162-239-195-175.8e41b6cde605491abfc1d33cf2d2d2f6.plex.direct:32400",
                        "local" => "0",
                        "relay" => "0",
                        "IPv6" => "0",
                        'online' => "0",
                    ],
                    [
                        "protocol" => "http",
                        "address" => "1050:0000:0000:0000:0005:0600:300c:326b",
                        "port" => "32400",
                        "uri" => "https://162-239-195-175.8e41b6cde605491abfc1d33cf2d2d2f6.plex.direct:32400",
                        "local" => "0",
                        "relay" => "0",
                        "IPv6" => "1",
                        'online' => "0",
                    ]
                ]
            ],
            [
                'name' => 'Test Server',
                'platform' => 'Linux',
                'device' => 'DS218',
                'publicAddress' => '8.8.8.8',
                'connections' => [
                    [
                        "protocol" => "http",
                        "address" => "192.168.1.4",
                        "port" => "32400",
                        "uri" => "https://192-168-1-4.e40b11e2e4aa41b0beaf81143f37e81a.plex.direct:32400",
                        "local" => "1",
                        "relay" => "0",
                        "IPv6" => "0",
                        'online' => "1",
                    ]
                ]
            ]
        ];

        // Load plex servers
        // $response = (new PlexTv)->getServers();

        // Error catching
        if (hasError($response)) {
            toast()->danger($response['error'], 'Plex API Error')->sticky()->push();
            return;
        }

        $this->servers = $response;

        // Test each server connection
        // $this->testConnections();

        $this->servers_loaded = true;
    }

    function testConnections(): void
    {
        foreach ($this->servers as $server_key => $server) {
            foreach ($server['connections'] as $connection_key => $connection) {
                $status = (new Plex($connection))->call('/myplex/account');
                $this->servers[$server_key]['connections'][$connection_key]['online'] = (!hasError($status) && array_key_exists('username', $status)) ? "1" : "0";
                // $this->servers[$server_key]['connections'][$connection_key]['status'] = $status;
            }
        }

        // dd($this->servers);
    }

    function selectConnection($server_key, $connection_key): void
    {
        dd($this->servers[$server_key]['connections'][$connection_key]);
    }
}
