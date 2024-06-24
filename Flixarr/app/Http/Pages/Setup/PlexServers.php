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
        $server_list = (new PlexApi)->plexTvCall('/api/resources');
        dd($server_list);
    }
}
