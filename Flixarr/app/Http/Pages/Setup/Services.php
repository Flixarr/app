<?php

namespace App\Http\Pages\Setup;

use App\Services\Radarr;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Services extends Component
{
    use WireToast;

    public bool $loading = true;
    public array $services = [];

    #[Layout('layouts.app', ['title' => 'Services'])]
    public function render()
    {
        return view('pages.setup.services');
    }

    public function load(): void
    {
        // Prevent user from visiting this page without a plex auth token
        if (!settings('plex_token')) {
            $this->redirect(route('setup.plex-auth'), false);

            return;
        }

        $this->loadServices();

        $this->loading = false;
    }

    public function loadServices(): void
    {
        $this->services = [
            'radarr' => [
                'image' => 'https://i.imgur.com/WDp2BhX.png',
                'ssl' => false,
                'host' => '192.168.1.3',
                'port' => '7878',
                'key' => '770d40dda2bd49148f83c89bf8b84055',
                'connected' => false,
            ],
            'sonarr' => [
                'image' => 'https://i.imgur.com/1c5IYiv.png',
                'ssl' => false,
                'host' => '192.168.1.3',
                'port' => '7878',
                'key' => '770d40dda2bd49148f83c89bf8b84055',
                'connected' => false,
            ],
        ];
    }

    public function submitService($service): void
    {
        // Initialize the Radarr Connection
        $initStatus = (new Radarr($this->services[$service]))->initConnection();

        if (hasError($initStatus, showToast: true)) {
            return;
        }

        // $connection[$service] = [
        //     'protocol' => $this->services[$service]['ssl'],
        //     'host' => $this->services[$service]['host'],
        //     'port' => $this->services[$service]['port'],
        //     'key' => $this->services[$service]['key'],
        // ];

        // $this->services[$service]['connected'] = true;

        // $this->dispatch('completed');

        // toast()->debug($service)->push();
    }

    public function resetServices(): void
    {
        $this->reset();
        $this->loadServices();
        $this->loading = false;
    }
}
