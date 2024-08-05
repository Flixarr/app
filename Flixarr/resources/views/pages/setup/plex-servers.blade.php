<x-layouts.minimal>
    <div wire:init="load">
        <div class="card-container">
            <div class="card card-padding">
                {{-- Card header --}}
                <div class="text-center">
                    <h2 class="card-title">Server Connection</h2>
                    <p class="card-desc">Next, select which server connection you would like to use.</p>
                </div>
                {{-- Card loading --}}
                <div class="flex flex-col space-y-5" wire:loading wire:target="load">
                    <x-loading />
                    <div class="text-sub text-center leading-6">
                        Retreiving and testing your server connections...<br>This may take a few seconds.
                    </div>
                </div>

                <div wire:loading.remove wire:target="load">
                    @if ($isLoaded)
                        <ul class="panel-flex">
                            @if (count($servers) > 0)
                                @foreach ($servers as $server_key => $server)
                                    <div x-data="{ open: false }" x-on:click.away="open = false">
                                        <div class="panel hover:panel-hover group flex space-x-5" :class="open && 'panel-active'" x-on:click="open = !open">
                                            <div class="flex-center">
                                                <div class="panel w-auto p-0">
                                                    <img class="aspect-square w-16 rounded transition" src="https://i.imgur.com/iuIRMzV.png" alt="" :class="open && 'panel-hover'">
                                                </div>
                                            </div>
                                            <div class="w-full">
                                                <div class="panel-title capitalize">{{ $server['name'] }}</div>
                                                <div class="panel-desc">{{ $server['platform'] . '-' . $server['device'] }} - {{ $server['publicAddress'] }}</div>
                                            </div>
                                            <div class="flex-center">
                                                <svg class="ms-3 h-8 w-8 text-muted transition" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 6l6 6l-6 6" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-center px-5" x-show="open" x-cloak x-collapse>
                                            <div class="panel rounded-t-none border-t-0 p-0">
                                                <ul class="divide-y" wire:loading.class="opacity-70">
                                                    @foreach ($server['connections'] as $connection_key => $connection)
                                                        <li class="@if ($connection['online']) hover:panel-active hover:cursor-pointer @else hover:cursor-not-allowed @endif space-y-1 p-3" @if ($connection['online']) wire:click="selectPlexConnection({{ $server_key }}, {{ $connection_key }})" @endif wire:loading.class="pointer-events-none hover:!cursor-wait">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex flex-col space-y-1 overflow-hidden">
                                                                    <div class="text-[8px] text-muted">
                                                                        @if (!$connection['online'])
                                                                            <span class="rounded-lg bg-red-500/40 px-2 py-0.5 text-white">Unreachable</span>
                                                                        @endif
                                                                        @if ($connection['protocol'] == 'https')
                                                                            <span class="rounded-lg bg-green-500/30 px-2 py-0.5 text-white">SSL</span>
                                                                        @endif
                                                                        @if ($connection['local'])
                                                                            <span class="rounded-lg bg-gray-700/40 px-2 py-0.5">Local</span>
                                                                        @else
                                                                            <span class="rounded-lg bg-gray-700/40 px-2 py-0.5">Remote</span>
                                                                        @endif
                                                                        @if ($connection['IPv6'])
                                                                            <span class="rounded-lg bg-gray-700/40 px-2 py-0.5">IPV6</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="@if (!$connection['online']) text-muted-dark @endif truncate">
                                                                        {{ $connection['address'] }} <span class="text-xs text-muted-dark">: {{ $connection['port'] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div wire:loading wire:target="selectPlexConnection({{ $server_key }}, {{ $connection_key }})">
                                                                    <x-loading size="w-6 h-6" />
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <li class="text-muted">
                                    <hr>
                                </li>
                                <li x-data="{ open: false }">
                                    <div class="panel hover:panel-hover group flex space-x-5" :class="open && 'panel-active'" x-on:click="open = !open">
                                        <div>
                                            <div class="flex-center panel h-[40px] w-[40px] p-0" :class="open && 'panel-hover'">
                                                <svg class="h-8 w-8 text-muted-dark transition" :class="open && '!text-muted'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 15l6 -6" />
                                                    <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                    <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <div class="text-lg">Custom Connection</div>
                                            <div class="text-xs text-muted transition">Enter the connection details manually</div>
                                        </div>
                                        <div class="flex-center">
                                            <svg class="ms-3 h-8 w-8 text-muted transition" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-center px-5" x-show="open" x-cloak x-transition.in>
                                        <div class="panel rounded-t-none border-t-0">
                                            <form class="grid grid-cols-3 gap-5" wire:submit.prevent="submitCustomConnection">
                                                <div class="col-span-2">
                                                    <x-forms.text label="Hostname / IP Address" placeholder="192.168.1.2" wiremodel="custom_connection.host" />
                                                </div>
                                                <div class="col-span-1">
                                                    <x-forms.text label="Port" placeholder="32400" wiremodel="custom_connection.port" />
                                                </div>
                                                <div class="col-span-full">
                                                    <div class="flex items-center justify-between">
                                                        <x-forms.toggle wire:model.live="custom_connection.ssl" label="Use SSL" />
                                                        <div class="flex-center space-x-3">
                                                            <div wire:loading wire:target="submitCustomConnection">
                                                                <x-loading size="w-7 h-7" />
                                                            </div>
                                                            <button class="button-primary button button-sm" type="submit">Connect</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                                <li class="text-center">
                                    <div class="text-sub">
                                        Did you sign into the wrong Plex account?<br>
                                        Don't worry, you can <a class="text-link" href="#" wire:click="resetPlexAuth">sign out</a> to start over.
                                    </div>
                                </li>
                            @else
                                <li class="panel flex-center flex-col space-y-3">
                                    {{-- <div class="px-5 py-3 text-sm text-center border rounded-lg bg-gray-900/30 border-gray-700/40 text-muted opacity-60"> --}}
                                    <svg class="h-16 w-16 text-muted opacity-20" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12h-6a3 3 0 0 1 -3 -3v-2c0 -1.083 .574 -2.033 1.435 -2.56m3.565 -.44h10a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-2" />
                                        <path d="M16 12h2a3 3 0 0 1 3 3v2m-1.448 2.568a2.986 2.986 0 0 1 -1.552 .432h-12a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h6" />
                                        <path d="M7 8v.01" />
                                        <path d="M7 16v.01" />
                                        <path d="M3 3l18 18" />
                                    </svg>
                                    <div class="flex max-w-sm select-text flex-col space-y-5">
                                        <div class="text-center text-muted">No server(s) found...</div>
                                        <div class="text-center text-sm text-muted-dark">Plex said that you do not have any servers associated with your Plex account. Refresh the page to check again.</div>
                                        <div class="text-center text-sm text-muted-dark">If you connected {{ config('app.name') }} with the wrong Plex account, you can <a class="text-link" href="#" wire:click="resetPlexAuth">sign out</a> to connect a different account.</div>
                                        <div class="text-center text-sm text-muted-dark">If you can not resolve the issue, check out <a class="text-link" href="https://support.plex.tv/articles/204604227-why-can-t-the-plex-app-find-or-connect-to-my-plex-media-server/" target="_blank">this article</a> for some helpful information, just pretend that "Plex App" is {{ config('app.name') }}.</div>
                                    </div>
                                    {{-- </div> --}}
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-padding text-center">
            <p class="text-sub">The server that you select must be online and accessible by {{ config('app.name') }}. If a server is <span class="rounded-lg bg-red-500/40 px-2 py-0.5 text-[8px] text-white">Unreachable</span>, you can refresh the page to check again. Remote hostnames may be unreachable due to the incorrect port number. To resolve this, you can use a custom connection and set the correct port number.</p>
        </div>
    </div>
</x-layouts.minimal>
