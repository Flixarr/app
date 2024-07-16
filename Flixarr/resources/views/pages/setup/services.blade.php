<div wire:init="load">
    <div class="card-container">
        <div class="card card-padding">
            {{-- Card header --}}
            <div class="text-center">
                <h2 class="card-title">Configure the <em>arrs!</em></h2>
                <p class="card-desc">Last step is to config Sonarr and Radarr.</p>
            </div>
            {{-- Card loading --}}
            <div wire:loading wire:target="load">
                <x-loading />
            </div>
        </div>
    </div>
</div>
