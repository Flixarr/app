<?php

namespace App\Http\Pages\Setup;

use App\Services\PlexApi;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class PlexServers extends Component
{
    use WireToast;

    public $server_list = [];

    public function render()
    {
        return view('pages.setup.plex-servers');
    }

    function load2(): void
    {
        // Load plex servers
        $this->server_list = (new PlexApi)->plexTvCall('/api/v2/resources', ['includeHttps' => "1"]);

        // toast()->debug($server_list)->push();

        // Grab results that include ['provides' => 'server'] and ['owned' => '1']
        // 'Provides' can be multiple enteries, but a server should only be 'server'

        // dd($server_list);
    }
}
