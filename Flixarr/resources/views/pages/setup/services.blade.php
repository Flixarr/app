<x-layouts.minimal>
    <div wire:init="load">
        <div class="card-container">
            <div class="card card-padding">
                {{-- Card header --}}
                <div class="text-center">
                    <h2 class="card-title">Additional Services</h2>
                    <p class="card-desc">Last step is to connect your additional services.</p>
                </div>
                {{-- Card loading --}}
                <div wire:loading.delay wire:target="load">
                    <x-loading />
                </div>

                <div class="panel-flex">
                    @foreach ($services as $service => $details)
                        <div x-data="{ open: false }" x-on:click.away="open = false" @completed.window="open = false">
                            <div class="panel hover:panel-hover group flex space-x-5" :class="open && 'panel-active'" x-on:click="open = !open">
                                <div class="flex-center">
                                    <div class="h-12 w-12 rounded">
                                        <img class="w-full shadow-white drop-shadow-2xl" src="{{ $details['image'] }}" alt="" :class="open && 'panel-hover'">
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="panel-title capitalize">{{ $service }}</div>
                                    <div class="panel-desc">Configure your <span class="capitalize">{{ $service }}</span> service</div>
                                </div>
                                <div class="flex-center" wire:key="{{ $service }}">
                                    @if ($details['connected'])
                                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                    @else
                                        <svg class="ms-3 h-8 w-8 text-muted transition" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 6l6 6l-6 6" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-center px-5" x-show="open" x-cloak x-collapse>
                                <div class="panel rounded-t-none border-t-0">
                                    <form class="form-grid" wire:submit.prevent="submit('{{ $service }}')">
                                        <div class="col-span-8">
                                            <x-forms.text label="Hostname / IP Address" placeholder="192.168.1.2" wiremodel="services.{{ $service }}.host" />
                                        </div>
                                        <div class="col-span-4">
                                            <x-forms.text label="Port" placeholder="32400" wiremodel="services.{{ $service }}.port" />
                                        </div>
                                        <div class="col-span-full">
                                            <x-forms.password label="API Key" wiremodel="services.{{ $service }}.key" />
                                        </div>
                                        <div class="col-span-6">
                                            <x-forms.toggle wire:model.live="services.{{ $service }}.ssl" label="Use SSL" />
                                        </div>
                                        <div class="col-span-6">
                                            <div class="flex-center !justify-end space-x-3">
                                                <div wire:loading wire:target="submit('{{ $service }}')">
                                                    <x-loading size="w-7 h-7" />
                                                </div>
                                                <button class="button-primary button button-sm" type="submit">Connect</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="button-container justify-between">
                    <button class="button button-muted button-sm button-wide">Reset</button>
                    <button class="button button-primary button-sm button-wide">Continue</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.minimal>
