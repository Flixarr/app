<div class="" x-data="{ step: @entangle('step') }" wire:init="initSetup">

    {{-- Loading --}}
    {{-- <div class="w-full mt-10" wire:loading>
        <x-loading />
    </div> --}}

    {{-- @env('local')
    <div class="flex items-center justify-between mb-5">
        <button wire:click="prevStep">Prev Step</button>
        <button wire:click="nextStep">Next Step</button>
    </div>
    @endenv --}}

    {{-- Plex Signin --}}
    {{-- @if ($step == 1) --}}
    {{-- <section x-show="step == 1" x-cloak>
        <livewire:pages.setup.plex-signin />
    </section> --}}
    {{-- @endif --}}

    {{-- Plex Servers --}}
    {{-- @if ($step == 2) --}}
    {{-- <section x-show="step == 2" x-cloak>
        <livewire:pages.setup.plex-servers lazy />
    </section> --}}
    {{-- @endif --}}

    {{-- Other Services --}}
    {{-- <section x-show="step == 3" x-cloak>
        <livewire:pages.setup.services lazy />
    </section> --}}

</div>
