<x-layouts.minimal>
    <div wire:init="load" x-data="{
        loading: @this.entangle('loading').live,
    }">
        <div class="card-container">
            <div class="card card-padding">

                {{-- Card header --}}
                <div class="text-center">
                    <h2 class="card-title">Additional Services</h2>
                    <p class="card-desc">Last step is to connect your additional services.</p>
                </div>

                {{-- Card loading --}}
                <div x-show="loading">
                    <x-loading />
                </div>

                <div x-show="!loading" x-cloak>
                    <div class="panel-flex">
                        @foreach ($services as $service => $details)
                            <div x-data="{ open: false }" x-on:click.away="open = false" @completed.window="open = false">
                                <div class="flex space-x-5 panel hover:panel-hover group" :class="open && 'panel-active'" x-on:click="open = !open">
                                    <div class="flex-center">
                                        <div class="w-12 h-12 rounded-sm">
                                            <img class="w-full shadow-white drop-shadow-2xl" src="{{ $details['image'] }}" alt="" :class="open && 'panel-hover'">
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="capitalize panel-title">{{ $service }}</div>
                                        @if ($details['connected'])
                                            <div class="panel-desc">{{ ($details['ssl'] ? 'https://' : 'http://') . $details['address'] . ':' . $details['port'] }}</div>
                                        @else
                                            <div class="panel-desc">Configure your <span class="capitalize">{{ $service }}</span> service</div>
                                        @endif
                                    </div>
                                    <div class="flex-center" wire:key="{{ $service }}">
                                        @if ($details['connected'])
                                            <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 transition ms-3 text-gray-500" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="px-5 flex-center" x-show="open" x-cloak x-collapse>
                                    <div class="border-t-0 rounded-t-none panel">
                                        <form class="form-grid" wire:submit.prevent="submitService('{{ $service }}')">
                                            <div class="col-span-8">
                                                <x-forms.text label="Hostname / IP Address" placeholder="192.168.1.10" wiremodel="services.{{ $service }}.address" />
                                            </div>
                                            <div class="col-span-4">
                                                <x-forms.text label="Port" placeholder="32400" wiremodel="services.{{ $service }}.port" />
                                            </div>
                                            <div class="col-span-full">
                                                <x-forms.password label="API Key" wiremodel="services.{{ $service }}.key" />
                                                <span class="form-desc">You can find this in Settings > General > Security.</span>
                                            </div>
                                            <div class="col-span-6">
                                                <x-forms.toggle wire:model.live="services.{{ $service }}.ssl" label="Use SSL" />
                                            </div>
                                            <div class="col-span-6">
                                                <div class="flex-center justify-end! space-x-3">
                                                    <div wire:loading wire:target="submitService('{{ $service }}')">
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
                        <div class="justify-between button-container">
                            <button class="button button-muted button-sm button-wide" type="button" wire:click="resetServices()" wire:confirm="Are you sure you want to reset the services? This will reset both Radarr and Sonarr.">Reset</button>
                            <button class="button button-primary button-sm button-wide" wire:click="continue">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.minimal>
