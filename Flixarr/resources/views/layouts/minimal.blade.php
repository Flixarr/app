<x-layouts.app title="{{ $title }}">

    <div class="flex flex-col py-12 space-y-12 tablet:py-24">

        <div class="mx-auto text-center">
            <x-logo />
        </div>

        <div class="sm:w-full tablet:mx-auto tablet:max-w-screen-phone">
            {{ $slot }}
        </div>

    </div>

</x-layouts.app>
