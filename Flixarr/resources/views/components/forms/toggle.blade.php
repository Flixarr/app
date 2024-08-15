<label class="form-toggle group h-[30px]" for="{{ str($label)->slug() }}">
    <input class="peer" id="{{ str($label)->slug() }}" type="checkbox" value="" {{ $attributes->whereStartsWith('wire:model') }} wire:loading.attr="disabled">
    <div class="peer-disabled:form-disabled peer group-focus-within:after:bg-white group-hover:after:bg-white peer-checked:bg-primary peer-checked:ring-primary peer-checked:after:translate-x-full peer-checked:after:border-white peer-checked:after:bg-white peer-focus:outline-none peer-focus:ring-primary"></div>
    <span>Use SSL</span>
</label>
