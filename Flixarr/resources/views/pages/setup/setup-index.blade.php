<div class="" x-data="{ step: @entangle('step') }" wire:init="load">

    {{-- Loading --}}
    <div class="w-full mt-10" wire:loading>
        <x-loading />
    </div>

    @env('local')
    <div class="flex items-center justify-between mb-5">
        <button wire:click="prevStep">Prev Step</button>
        <button wire:click="nextStep">Next Step</button>
    </div>
    @endenv

    {{-- Plex Signin --}}
    <section x-show="step == 1" x-cloak>
        <livewire:pages.setup.plex-signin />
    </section>

    {{-- Plex Servers --}}
    <section x-show="step == 2" x-cloak>
        <livewire:pages.setup.plex-servers />
    </section>

    {{-- Other Services --}}
    <section x-show="step == 3" x-cloak>
        <livewire:pages.setup.services />
    </section>

</div>
