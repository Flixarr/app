<div class="h-full text-center" x-data="plexSignin" wire:init="load">
    <div class="card-container">
        <div class="card">
            <div>
                <h2 class="card-title">Welcome to {{ config('app.name') }}!</h2>
                <p class="card-desc">First things first, we need to connect to your Plex Account.</p>
            </div>
            <div class="">
                <div x-show="!loading">
                    <button class="text-base button button-primary" x-on:click="initPlexAuth">
                        Sign in with Plex
                    </button>
                </div>
                <div class="flex justify-center" x-show="loading" x-cloak>
                    <svg class="w-[37px] h-[37px] text-blue-100 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="card-spacing">
        <p class="text-sub">Connecting your Plex account allows Flixarr to communicate with your Plex Media Server. Your login details are never visible to Flixarr. More information can be found <a class="text-link" href="#" target="_blank">here</a>.</p>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            let plexWindow
            let poll
            let pollingInterval = 2000 // How often should we poll for authentication (in milliseconds)
            Alpine.data('plexSignin', () => ({
                // Hide/Disable sign in button - Show loading icon
                loading: false,
                // Initialize Plex Authentication
                initPlexAuth() {
                    // Init Loading State
                    this.loading = true
                    // Open a seperate window, redirect to the loading page
                    plexWindow = window.open('/loading')
                    // Start Plex Authentication
                    console.log('Calling initPlexAuth()...')
                    @this.initPlexAuth().then(status => {
                        // If initalization was successful, then continue
                        if (status) {
                            console.log('Plex initialized.')
                            // Once Plex Authentication has started, get Plex Auth URL
                            console.log('Calling getPlexAuthUrl()...')
                            @this.getPlexAuthUrl().then(url => {
                                if (url) {
                                    console.log('Setting Plex window to: ' + url)
                                    // Init polling for successful authentication
                                    this.initPlexPolling()
                                    // Redirect to the User's Auth Url
                                    plexWindow.location = url
                                } else {
                                    console.log('Error getting URL.')
                                    // Quit Plex Auth
                                    this.quitPlexAuth()
                                }
                            })
                        } else {
                            console.log('Error initializing Plex Auth.')
                            // Quit Plex Auth
                            this.quitPlexAuth()
                        }
                    })
                },
                // Initialize polling for successful authentication
                initPlexPolling() {
                    console.log('Initializing Plex polling...')
                    // This poll repetitively checks if the user has authenticated yet.
                    // The poll also checks to see if the user has closed the Plex Window prematurely
                    poll = setInterval(() => {
                        console.log('Polling Plex...')
                        // Check if the user has authenticated yet
                        @this.plexAuth().then(response => {
                            console.log(response)
                            // The response will either be true for successful authentication, an array for an error,
                            // or false for an unsuccessful auth. If the auth was unsuccessful, we need to keep polling
                            // until it is.

                            // If the plex auth window was closed prematurely, stop polling and dispatch notifcation
                            if (plexWindow.closed) {
                                // Quit Plex Auth
                                this.quitPlexAuth()
                            }

                            // If the response is not null/false, stop polling
                            if (response) {
                                // Quit Plex Auth
                                this.quitPlexAuth()
                                // If response was bool, auth completed
                                if (typeof response == 'boolean') {
                                    console.log('Calling plexAuthCompleted...')
                                    @this.plexAuthCompleted();
                                }
                            }
                        })
                    }, pollingInterval);
                },
                quitPlexAuth() {
                    console.log('Plex Auth ended.')
                    // End polling
                    clearInterval(poll)
                    // Close the plex window
                    plexWindow.close()
                    // Disable loading state
                    this.loading = false
                }
            }))
        })
    </script>
@endpush
