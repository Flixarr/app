<div class="h-full text-center" x-data="plexSignin">
    <div class="card-container">
        <div class="card">
            <div>
                <h2 class="card-title">Connect your Plex Account</h2>
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
            let pollingInterval = 2500 // How often should we poll for authentication (in milliseconds)
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
                    @this.initPlexAuth().then(status => {
                        // If initalization was successful, then continue
                        if (status) {
                            // Once Plex Authentication has started, get Plex Auth URL
                            @this.getPlexAuthUrl().then(url => {
                                if (url) {
                                    // Init polling for successful authentication
                                    this.initPlexPolling()
                                    // Redirect to the User's Auth Url
                                    plexWindow.location = url
                                } else {
                                    // Quit Plex Auth
                                    this.quitPlexAuth()
                                }
                            })
                        } else {
                            // Quit Plex Auth
                            this.quitPlexAuth()
                        }
                    })
                },
                // Initialize polling for successful authentication
                initPlexPolling() {
                    // This poll repetitively checks if the user has authenticated yet.
                    // The poll also checks to see if the user has closed the Plex Window prematurely
                    poll = setInterval(() => {
                        // Check if the user has authenticated yet
                        @this.plexAuth().then(response => {
                            // A response is given on each check, the response can either be true, false, or an array
                            // A true response means that the auth was successful
                            // A false response means that the auth hasn't happened yet, and we need to check again
                            // An array response means an error happened.

                            // If the response is not null, stop polling
                            if (response) {
                                // Quit Plex Auth
                                this.quitPlexAuth()
                            }
                        })
                        // If the plex auth window was closed prematurely, stop polling and dispatch notifcation
                        if (plexWindow.closed) {
                            // Quit Plex Auth
                            this.quitPlexAuth()
                            // Dispatch notifcation
                            Toast.warning('The authentication window was closed prematurely. Please try again.', 'Authentication Failed')
                        }
                    }, pollingInterval);
                },
                quitPlexAuth() {
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
