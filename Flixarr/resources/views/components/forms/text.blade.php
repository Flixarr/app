<label class="form-text" for="{{ $wiremodel }}">
    <span class="@error($wiremodel) form-label-error @enderror">{{ $label }}</span>
    <input class="form-input @error($wiremodel) form-input-error @enderror" id="{{ $wiremodel }}" name="{{ $wiremodel }}" type="text" wire:model="{{ $wiremodel }}" placeholder="{{ $placeholder }}" wire:loading.attr="disabled">
</label>
{{--
<div class="form-text" wire:loading.class="opacity-30">
    <label>{{ $label }}</label>
    <input class="block w-full py-2 border-0 rounded-lg shadow-sm bg-gray-800/50 ring-1 ring-inset ring-gray-700/40 placeholder:text-muted/50 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" id="server" name="server" type="text" {{ $attributes->whereStartsWith('wire:model') }} placeholder="{{ $placeholder }}">
</div> --}}
