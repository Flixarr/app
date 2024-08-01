<x-layouts.minimal>
    <div x-data="{
        step: $wire.entangle('step'),
        open(step) {
            this.step = step
        },
        isOpen(step) {
            return this.step === step
        },
        next() {
            this.step++
        },
        prev() {
            this.step--
        }
    }" wire:init="load">
        <div class="card-container">
            <div class="card card-padding">
                {{-- Card header --}}
                <div class="text-center">
                    <h2 class="card-title">Configure the <em>arrs!</em></h2>
                    <p class="card-desc">Last step is to config your additional services.</p>
                </div>
                {{-- Card loading --}}
                <div wire:loading.delay>
                    <x-loading />
                </div>

                <div class="card-flex">
                    <div class="panel" @click="open(1)">
                        <div class="flex space-x-5">
                            <div class="flex items-center">
                                <img class="object-contain w-12 h-12" src="https://i.imgur.com/KeLti6e.png" alt="">
                            </div>
                            <div class="w-full">
                                <h3 class="panel-title">Radarr</h3>
                                <p class="panel-desc">Configure your Radarr service below.</p>
                            </div>
                            <div class="flex-center">
                                <svg class="w-8 h-8 transition ms-3 text-muted" :class="isOpen(1) && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 6l6 6l-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div x-show="isOpen(1)" x-collapse x-cloak>
                            <form class="grid grid-cols-3 gap-5 mt-5" wire:submit.prevent="submitCustomConnection">
                                <div class="col-span-2">
                                    <x-forms.text label="Hostname / IP Address" placeholder="192.168.1.2" wiremodel="custom_connection.host" />
                                </div>
                                <div class="col-span-1">
                                    <x-forms.text label="Port" placeholder="32400" wiremodel="custom_connection.port" />
                                </div>
                                <div class="col-span-full">
                                    <x-forms.text label="API Key" wiremodel="sonarr.api_key" />
                                </div>
                                <div class="col-span-full">
                                    <div class="flex items-center justify-between">
                                        <x-forms.toggle wire:model.live="custom_connection.ssl" label="Use SSL" />
                                        <div class="space-x-3 flex-center">
                                            <div wire:loading wire:target="submitCustomConnection">
                                                <x-loading size="w-7 h-7" />
                                            </div>
                                            <button class="button-primary button button-sm" type="submit">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel" @click="open(2)">
                        <div class="flex space-x-5">
                            <div class="flex items-center">
                                <img class="object-contain w-12 h-12" src="https://i.imgur.com/XHHNn60.png" alt="">
                            </div>
                            <div class="w-full">
                                <h3 class="panel-title">Sonarr</h3>
                                <p class="panel-desc">Configure your Sonarr service below.</p>
                            </div>
                            <div class="flex-center">
                                <svg class="w-8 h-8 transition ms-3 text-muted" :class="isOpen(2) && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 6l6 6l-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div x-show="isOpen(2)" x-collapse x-cloak>
                            <form class="grid grid-cols-3 gap-5 mt-5" wire:submit.prevent="submitCustomConnection">
                                <div class="col-span-2">
                                    <x-forms.text label="Hostname / IP Address" placeholder="192.168.1.2" wiremodel="custom_connection.host" />
                                </div>
                                <div class="col-span-1">
                                    <x-forms.text label="Port" placeholder="32400" wiremodel="custom_connection.port" />
                                </div>
                                <div class="col-span-full">
                                    <x-forms.text label="API Key" wiremodel="sonarr.api_key" />
                                </div>
                                <div class="col-span-full">
                                    <div class="flex items-center justify-between">
                                        <x-forms.toggle wire:model.live="custom_connection.ssl" label="Use SSL" />
                                        <div class="space-x-3 flex-center">
                                            <div wire:loading wire:target="submitCustomConnection">
                                                <x-loading size="w-7 h-7" />
                                            </div>
                                            <button class="button-primary button button-sm" type="submit">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.minimal>
