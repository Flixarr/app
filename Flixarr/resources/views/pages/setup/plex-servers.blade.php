<div class="h-full text-center" x-data="plexSignin" wire:init="load2">
    <div class="card-container">
        <div class="card">
            <div>
                <h2 class="card-title">Connect your Plex Server</h2>
                <p class="card-desc">Next, select which server connection you would like to use.</p>
            </div>
            <div class="">

                @if ($server_list)
                    <ul>
                        <li>
                            <label class="p-5 bg-gray-800" for="">
                                <input id="" name="" type="radio">
                                <div>
                                    helo
                                </div>
                            </label>
                        </li>
                    </ul>
                @endif

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
