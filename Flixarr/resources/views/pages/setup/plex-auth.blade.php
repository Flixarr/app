<x-layouts.minimal>
    <div x-data="plexSignin" wire:init="load">
        <div class="card-container">
            <div class="card card-padding">
                {{-- Card header --}}
                <div class="text-center">
                    <h2 class="card-title">Welcome to {{ config('app.name') }}!</h2>
                    <p class="card-desc">First things first, we need to connect your Plex Account.</p>
                </div>

                <div class="mx-auto" x-show="loading">
                    <x-loading />
                </div>

                <div class="text-center" x-show="!loading" x-cloak>
                    <button class="text-base button button-primary button-w" x-on:click="initPlexAuth">
                        Sign in with Plex
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center card-padding">
            <p class="text-sub">
                Connecting your Plex account allows Flixarr to communicate with your Plex Media Server.
                Your login details are never visible to Flixarr. More information can be found <a class="text-link"href="#" target="_blank">here</a>.
            </p>
        </div>
    </div>
</x-layouts.minimal>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            let plexWindow
            let poll
            let pollingInterval = 2000 // How often should we poll for authentication (in milliseconds)
            Alpine.data('plexSignin', () => ({
                // Hide/Disable sign in button - Show loading icon
                loading: @this.entangle('loading').live,
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
                                console.log('Plex window closed prematurely.')
                                // Quit Plex Auth
                                this.quitPlexAuth()
                                // Cancel loading
                                this.loading = false
                            }

                            // If the response is not null or false, stop polling
                            if (response) {
                                // Quit Plex Auth
                                this.quitPlexAuth()
                                // If response was bool, auth completed
                                if (response === true) {
                                    @this.plexAuthCompleted()
                                }
                            }
                        })
                    }, pollingInterval);
                },
                quitPlexAuth() {
                    console.log('Cancelling Plex Auth...')
                    // End polling
                    clearInterval(poll)
                    // Close the plex window
                    plexWindow.close()
                    console.log('Plex Auth cancelled.')
                }
            }))
        })
    </script>
@endpush
