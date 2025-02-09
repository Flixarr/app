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
        if (!settings('plex.auth.token')) {
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
                'address' => '192.168.1.1',
                'port' => '35504',
                'key' => '38c65058a7b646a5a908b54fb0492c6c',
                'connected' => false,
            ],
            'sonarr' => [
                'image' => 'https://i.imgur.com/1c5IYiv.png',
                'ssl' => false,
                'address' => '',
                'port' => '8989',
                'key' => '',
                'connected' => false,
            ],
        ];
    }

    public function submitService($service): void
    {
        // Initialize the Radarr Connection
        $initStatus = (new Radarr($this->services[$service]))->initConnection();

        if (hasError($initStatus, logError: true, showToast: true, toastType: "warning", stickyToast: false)) {
            return;
        }

        // Set service as connected
        $this->services[$service]['connected'] = true;

        // Dispatch completed actions to minimize service panel
        $this->dispatch('completed');
    }

    public function resetServices(): void
    {
        $this->reset();
        $this->loadServices();
        $this->loading = false;
    }
}
