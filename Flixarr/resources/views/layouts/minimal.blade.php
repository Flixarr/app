<div class="flex flex-col h-full py-5 space-y-5 overflow-y-auto tablet:py-24">
    <div class="mx-auto text-center">
        <x-logo />
    </div>
    <div class="sm:w-full tablet:mx-auto tablet:max-w-screen-phone">
        {{ $slot }}
    </div>
</div>
