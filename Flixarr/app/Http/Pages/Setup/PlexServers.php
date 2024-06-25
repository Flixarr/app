<?php

namespace App\Http\Pages\Setup;

use App\Services\PlexApi;
use Livewire\Component;

class PlexServers extends Component
{
    public $server_list = [];

    public function render()
    {
        return view('pages.setup.plex-servers');
    }

    function initPlexServers(): void
    {
        // Load plex servers
        $server_list = (new PlexApi)->plexTvCall('/api/v2/resources', ['includeHttps' => "1"]);

        // Grab results that include ['provides' => 'server'] and ['owned' => '1']
        // 'Provides' can be multiple enteries, but a server should only be 'server'

        dd($server_list);
    }
}
