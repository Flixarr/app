{{-- <x-layouts.app title="{{ $title }}"> --}}
<div class="flex flex-col h-full py-5 space-y-5 overflow-y-auto tablet:py-24">
    @if (!$hide_logo)
        <div class="mx-auto text-center">
            <x-logo />
        </div>
    @endif
    <div class="sm:w-full tablet:mx-auto tablet:max-w-screen-phone">
        {{ $slot }}
    </div>
</div>
{{-- </x-layouts.app> --}}
