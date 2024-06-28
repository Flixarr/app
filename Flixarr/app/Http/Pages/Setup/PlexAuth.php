<?php

namespace App\Http\Pages\Setup;

use App\Services\PlexApi;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class PlexAuth extends Component
{
    use WireToast;

    /**
     * Render the Plex Signin button
     *
     * @return View
     */
    #[Layout('layouts.minimal', ['title' => 'Plex Authentication'])]
    function render(): View
    {
        return view('pages.setup.plex-auth');
    }

    // Comment
    function load(): void
    {
        // Verify the user is not already authenticated
    }

    /**
     * Initialize Plex Authentication
     *
     * @return bool
     */
    function initPlexAuth(): bool
    {
        // Generate Plex Auth PIN for this session
        $response = (new PlexApi())->generateAuthPin();

        // Check for errors from response
        if (hasError($response)) {
            toast()->danger($response['error'], 'Plex API Error')->sticky()->push();
            return false;
        }

        // Check for missing plex auth pin
        if (session()->missing('plex_auth_pin')) {
            toast()->danger('There was a problem with Plex\'s API. Refresh the page and try again. (Missing Authentication PIN)', 'Plex API Error')->sticky()->push();
            return false;
        }

        return true;
    }

    /**
     * Returns the Plex Authentication URL to redirect the Plex Popup to the Plex sign in page
     *
     * @return string|bool
     */
    function getPlexAuthUrl(): string|bool
    {
        // Get response from Plex API
        $response = (new PlexApi)->authUrl();

        // If response is an array, something bad happened
        if (hasError($response)) {
            toast()->danger($response['error'], 'Plex API Error')->sticky()->push();
            return false;
        }

        // Return Auth URL
        return $response;
    }

    /**
     * Plex Authentication
     *
     * This function
     *
     * @return bool|array
     */
    public function plexAuth(): bool|array
    {
        // Get the authentication response from the local Plex API
        $response = (new PlexApi)->authenticate();

        // If there was an error, dispatch notification
        if (hasError($response)) {
            toast()->danger($response['error'], 'Plex API Error')->sticky()->push();
            return true;
        }

        // If response was true, authentication was successful, test authentication
        if ($response === true) {


            $this->dispatch('setup-next-step');
        }

        return $response;
    }
}
