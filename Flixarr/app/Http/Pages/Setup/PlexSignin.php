<?php

namespace App\Http\Pages\Setup;

use App\Services\PlexApi;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class PlexSignin extends Component
{
    use WireToast;

    /**
     * Render the Plex Signin button
     *
     * @return View
     */
    function render(): View
    {
        return view('pages.setup.plex-signin');
    }

    /**
     * Returns the Plex Authentication URL to redirect the Plex Popup to the Plex sign in page
     *
     * @return string
     */
    function getPlexAuthUrl(): string
    {
        // Return the Plex Auth Url
        $authUrl = (new PlexApi)->authUrl();

        if (!$authUrl) {
            toast()->danger('Invalid Plex Auth PIN. Please try again later. (1)', 'Plex API Error')->sticky()->push();
        }

        return $authUrl;
    }

    /**
     * 
     *
     * @return bool
     */
    public function getPlexAuthStatus(): array|string
    {
        // Get the authentication status
        $status = (new PlexApi)->authStatus();

        if (is_array($status)) {
            if (array_key_exists('error', $status)) {
                toast()->danger($status['error'])->sticky()->push();
                return [
                    'error' => 'asdf'
                ];
            }
        }

        return $status;

        // if (is_array($status) && array_key_exists('error', $status)) {
        //     toast()->danger($status['error'])->sticky()->push();
        //     return false;
        // } else {
        //     return true;
        // }

        // if ($status['status'] === 'error') {
        //     toast()->debug($status)->sticky()->push();
        //     return [
        //         'error' => '',
        //     ];
        // }

        // if ($status['status'] === 'notclaimed') {
        //     return false;
        // }

        // if ($status['status'] === 'claimed') {
        //     // Settings::set('plex_authToken', $status['data']['authToken']);
        //     // $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Successfully signed in!']);
        //     return 'claimed';
        // }
    }

    function plexAuthCompleted()
    {
        // Once the authentication has been completed, we need to save the Access Token

        toast()->debug('completed')->push();
        // (new PlexApi)->updateAccessToken();
    }
}
