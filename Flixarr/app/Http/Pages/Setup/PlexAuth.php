<?php

namespace App\Http\Pages\Setup;

use App\Services\PlexTv;
use App\Traits\WithLivewireLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;
use Usernotnull\Toast\Concerns\WireToast;

#[Title('Setup')]
class PlexAuth extends Component
{
    use WireToast, WithLivewireLogger;

    // Loading state
    public bool $loading = true;

    /**
     * Render the Plex Signin button
     * asdf
     */
    public function render(): View
    {
        return view('pages.setup.plex-auth');
    }

    // Comment
    public function load(): void
    {
        // Verify the user is not already authenticated
        if (settings('plex.auth.token')) {
            $this->redirect(route('setup.plex-servers'), false);

            return;
        }

        // Update loading state
        $this->loading = false;
    }

    /**
     * Initialize Plex Authentication
     */
    public function initPlexAuth(): bool
    {
        $this->loading = true;

        // Generate Plex Auth PIN for this session
        $response = (new PlexTv)->generateAuthPin();

        // Check for errors from response
        return hasError($response, showToast: true, toastTitle: 'Plex API Error') ? false : true;
    }

    /**
     * Returns the Plex Authentication URL to redirect the Plex Popup to the Plex sign in page
     */
    public function getPlexAuthUrl(): bool|string
    {
        // Return the Auth URL
        return (new PlexTv)->authUrl();
    }

    /**
     * Plex Authentication
     *
     * This function is the auth check function that the frontend will request each
     * time it polls. It checks if the auth pin has been registered and a valid
     * auth token was given
     *
     * @return  array|bool
     */
    public function plexAuth(): HandlesRedirects|array|bool
    {
        // Get the authentication response from the local Plex API
        $response = (new PlexTv)->authenticate();

        // If there was an error, dispatch notification
        if (hasError($response, showToast: true, toastTitle: 'Plex API Error')) {
            // Return not null to stop polling
            return ['error' => $response['data']['message']];
        }

        // If response was true, authentication was successful, test authentication
        if ($response === true) {
            // Returning false to shut up vscode, and to prevent the user from clicking the signin button again
            return true;
        }

        return $response;
    }

    public function plexAuthCompleted(): bool
    {
        $plexTv = new PlexTv;

        $response = $plexTv->call('/users/account');

        // dd($response);

        // Check if plex auth was actually completed
        if ($plexTv->verifyAuth()) {
            // Auth was successful
            // Save plex user data
            $plexTv->savePlexUserData();
            // Redirect to the next step
            $this->redirect(route('setup.plex-servers'), navigate: false);
        } else {
            toast()->danger('There was a problem authenticating your Plex account. Please refresh the page and try again.', 'Plex Authentication Error')->push();
            $this->loading = false;
        }

        return false;
    }
}
