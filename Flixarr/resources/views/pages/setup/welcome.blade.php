<x-layouts.minimal title="test">
    <div>
        <div class="card-container">
            <div class="card card-padding">
                {{-- Card header --}}
                <div>
                    <h2 class="card-title">Welcome to {{ config('app.name') }}!</h2>
                    <p class="card-desc">First things first, we need to connect to your Plex Account.</p>
                </div>
                {{-- Card loading --}}
                <div x-show="loading" x-cloak>
                    <x-loading />
                </div>
                {{-- Card content --}}
                <div class="text-center" x-show="!loading">
                    <button class="text-base button button-primary" x-on:click="initPlexAuth">
                        Sign in with Plex
                    </button>
                </div>
            </div>
        </div>
        <div class="text-center card-padding">
            <p class="text-sub">Connecting your Plex account allows Flixarr to communicate with your Plex Media Server. Your login details are never visible to Flixarr. More information can be found <a class="text-link" href="#" target="_blank">here</a>.</p>
        </div>
    </div>

</x-layouts.minimal>
