<?php

namespace App\Http\Pages\Setup;

use App\Services\PlexTv;
use App\Traits\WithLivewireLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;
use Usernotnull\Toast\Concerns\WireToast;

class PlexAuth extends Component
{
    use WireToast, WithLivewireLogger;

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
        if (settings('plex_token')) {
            $this->redirect(route('setup.plex-servers'), false);
            return;
        }
    }

    /**
     * Initialize Plex Authentication
     *
     * @return bool
     */
    function initPlexAuth(): bool
    {
        // Generate Plex Auth PIN for this session
        $response = (new PlexTv())->generateAuthPin();

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
        $response = (new PlexTv)->authUrl();

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
     * This function is the auth check function that the frontend will request each
     * time it polls. It checks if the auth pin has been registered and a valid
     * auth token was given
     *
     * @return bool|array
     */
    public function plexAuth(): bool|array|HandlesRedirects
    {
        // Get the authentication response from the local Plex API
        $response = (new PlexTv)->authenticate();

        // If there was an error, dispatch notification
        if (hasError($response)) {
            // Dispatch notification
            toast()->danger($response['error'], 'Plex API Error')->sticky()->push();
            // Return true to stop polling
            return ['error' => $response['data']['message']];
        }

        // If response was true, authentication was successful, test authentication
        if ($response === true) {
            // Returning false to shut up vscode, and to prevent the user from clicking the signin button again
            return true;
        }

        return $response;
    }

    function plexAuthCompleted(): void
    {
        // Check if plex auth was actually completed
        if ((new PlexTv)->verifyAuth()) {
            // Dispatch notification
            toast()->success('Plex successfully connected!')->push();
            // Redirect to next step
            $this->redirect(route('setup.plex-servers'), navigate: false);
        } else {
            toast()->danger('There was a problem authenticating your Plex account.', 'Plex Authentication Error')->push();
        }
    }
}
