{{-- <div class="flex flex-col h-full py-5 space-y-5 overflow-y-auto tablet:py-24">
    <div class="mx-auto text-center">
        <x-logo />
    </div>
    <div class="sm:w-full tablet:mx-auto tablet:max-w-(--breakpoint-phone)">
        {{ $slot }}
    </div>
</div> --}}

<div class="w-full">
    <div class="minimal-container">
        <div class="flex flex-col space-y-10">
            <div class="mx-auto text-center">
                <x-logo />
            </div>
            <div class="mx-auto text-center">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
