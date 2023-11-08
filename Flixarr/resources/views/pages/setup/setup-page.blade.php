<div class="" x-data="{ state: @entangle('state') }">

    <section x-show="state == 'signin'">
        <livewire:pages.setup.plex-signin />
    </section>

    <section x-show="state == 'servers'" x-cloak>
        <livewire:pages.setup.plex-servers />
    </section>

    <section x-show="state == 'services'" x-cloak>
        <livewire:pages.setup.services />
    </section>

</div>
