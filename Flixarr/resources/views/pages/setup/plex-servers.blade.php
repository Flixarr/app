<div class="h-full text-center" x-data="plexSignin" wire:init="initPlexServers">
    <div class="card-container">
        <div class="card">
            <div>
                <h2 class="card-title">Connect your Plex Server</h2>
                <p class="card-desc">Next, select which server connection you would like to use.</p>
            </div>
            <div class="">
                <fieldset>
                    <div class="space-y-4">
                        <label for=""></label>
                    </div>
                </fieldset>

                <fieldset aria-label="Server size">
                    <div class="space-y-4">
                        <!-- Active: "border-indigo-600 ring-2 ring-indigo-600", Not Active: "border-gray-300" -->
                        <label class="relative block px-6 py-4 bg-white border rounded-lg shadow-sm cursor-pointer focus:outline-none sm:flex sm:justify-between" aria-label="Hobby" aria-description="8GB, 4 CPUs, 160 GB SSD disk, $40 per month">
                            <input class="sr-only" name="server-size" type="radio" value="Hobby">
                            <span class="flex items-center">
                                <span class="flex flex-col text-sm">
                                    <span class="font-medium text-gray-900">Hobby</span>
                                    <span class="text-gray-500">
                                        <span class="block sm:inline">8GB / 4 CPUs</span>
                                        <span class="hidden sm:mx-1 sm:inline" aria-hidden="true">&middot;</span>
                                        <span class="block sm:inline">160 GB SSD disk</span>
                                    </span>
                                </span>
                            </span>
                            <span class="flex mt-2 text-sm sm:ml-4 sm:mt-0 sm:flex-col sm:text-right">
                                <span class="font-medium text-gray-900">$40</span>
                                <span class="ml-1 text-gray-500 sm:ml-0">/mo</span>
                            </span>
                            <span class="absolute border-2 rounded-lg pointer-events-none -inset-px" aria-hidden="true"></span>
                        </label>
                    </div>
                </fieldset>

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
