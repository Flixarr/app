<div class="h-full text-center" x-data="plexSignin">
    <div class="card-container">
        <div class="flex flex-col justify-center card">
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
        <p class="text-sub">Connecting your Plex Account allows Flixarr to retrieve your Plex profile, server details, connection info, libraries, and users with access to your library. This information is never shared. All data is stored on your local machine. More information can be found <a class="text-link" href="#" target="_blank">here</a>.</p>
        <p class="text-sub">Flixarr needs to connect to your Plex account to retreive an access token to be able to pull your server details, libraries, </p>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            var plexWindow
            var poll

            Alpine.data('plexSignin', () => ({
                // To prevent the user from causing multiple authentication requests,
                // we need to disable the "sign in with plex" button after the user
                // presses the button for the first time. When loading is enabled, 
                // the sign in button is hidden/disabled and a loading icon is shown
                loading: false,

                // Initialize Plex Authentication
                initPlexAuth() {
                    // Init Loading State
                    this.loading = true

                    // Open a seperate window, redirect to the loading page
                    plexWindow = window.open('/loading')

                    // Generate a User's Authentication URL, then redirect the Plex Window to that URL
                    @this.getPlexAuthUrl().then(authUrl => {
                        if (authUrl) {
                            // Redirect to the User's Auth Url
                            plexWindow.location = authUrl
                            // Init polling for successful authentication
                            this.initPlexPolling()
                        }
                    })
                },

                // Initialize polling for successful authentication
                initPlexPolling() {
                    // This poll repetitively checks if the user has authenticated yet.
                    // Once the user has authenticated, the Auth Token is saved.
                    // The poll also checks to see if the user has closed the Plex Window prematurely
                    var poll = setInterval(() => {
                        // Check if the user has authenticated yet
                        @this.getPlexAuthStatus().then(userAuthenticated => {
                            console.log(userAuthenticated)
                            // Once the user has authenticated, close the plex window and redirect
                            if (userAuthenticated) {
                                // End polling
                                clearInterval(poll)
                                // Close the plex window
                                plexWindow.close()
                                // Continue with setup
                                @this.plexAuthCompleted()

                                // If array was returned, error happened
                                if (userAuthenticated instanceof Array) {
                                    er
                                }

                                // If true was returned, authentication was successful
                                if (userAuthenticated instanceof Boolean) {

                                }
                            }
                        })

                        // Check if the user has closed the plex windows prematurely
                        if (plexWindow.closed) {
                            // Disable loading state
                            this.loading = false
                            // End polling
                            clearInterval(poll)
                            // Dispatch notification of failed authentication.
                            Toast.warning('The authentication window was closed. Please try again.', 'Authentication Failed')
                        }
                    }, 2000);




                    // // This poll repetitively checks the status of the User's Plex authentication
                    // var poll = setInterval(() => {

                    //     // Get the status of the User's Plex Authentication
                    //     @this.getUsersPlexAuthStatus().then(status => {
                    //         // The status of the pin should return as "notclaimed"
                    //         // 
                    //         // If the status of the pin returns as anything other than "not claimed",
                    //         // It will return as "claimed" or "error"
                    //         if (status != 'notclaimed') {
                    //             // If the status is returned as "claimed"
                    //             if (status === 'claimed') {
                    //                 // Stop the polling
                    //                 clearInterval(poll)
                    //                 // Close the plex window
                    //                 plexWindow.close()
                    //                 // Continue with setup
                    //                 @this.plexAuthCompleted()
                    //             }

                    //             // If the status is returned as "error"
                    //             if (status === "error") {
                    //                 // Close the plex window
                    //                 plexWindow.close()
                    //                 // Disable loading state
                    //                 this.loading = false
                    //                 // End polling
                    //                 clearInterval(poll)
                    //                 // Dispatch notification of failed authentication.
                    //                 Toast.danger('Looks like there is an issue with Plex\'s API. Please wait a few minutes and try again.', 'API Error', 0)
                    //             }
                    //         }
                    //     })

                    //     // Check if the user prematurely closed the Plex window
                    //     if (plexWindow.closed) {
                    //         // Disable loading state
                    //         this.loading = false
                    //         // End polling
                    //         clearInterval(poll)
                    //         // Dispatch notification of failed authentication.
                    //         Toast.danger('The authentication window was closed. Please try again.', 'Authentication Failed')
                    //     }
                    // }, 2000)
                }
            }))
        })
    </script>
@endpush
