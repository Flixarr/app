<label class="form-text" for="{{ $wiremodel }}">
    <span class="@error($wiremodel) form-label-error @enderror">{{ $label }}</span>
    <input class="@error($wiremodel) form-input-error @enderror form-input" id="{{ $wiremodel }}" name="{{ $wiremodel }}" type="{{ $type }}" wire:model="{{ $wiremodel }}" placeholder="{{ $placeholder }}" wire:loading.attr="disabled">
</label>
