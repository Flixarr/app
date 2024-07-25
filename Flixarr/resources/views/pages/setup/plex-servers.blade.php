<div wire:init="load">
    <div class="card-container">
        <div class="card card-padding">
            {{-- Card header --}}
            <div class="text-center">
                <h2 class="card-title">Connect your Plex Server</h2>
                <p class="card-desc">Next, select which server connection you would like to use.</p>
            </div>
            {{-- Card loading --}}
            <div wire:loading wire:target="load">
                <x-loading />
            </div>

            <div wire:loading.remove wire:target="load">
                @if ($servers_loaded)
                    <ul class="card-flex">
                        @if (count($servers) > 0)
                            @foreach ($servers as $server_key => $server)
                                <div x-data="{ open: false }" x-on:click.away="open = false">
                                    <div class="flex space-x-5 panel hover:panel-hover group" :class="open && 'panel-active'" x-on:click="open = !open">
                                        <div class="w-auto p-0 panel">
                                            <img class="w-16 transition rounded aspect-square" src="https://preview.redd.it/new-plex-logo-v0-5x93lknmuaw81.jpg?auto=webp&s=a8edd33ea3d1f38929c7917abea05291ad49f528" alt="" :class="open && 'panel-hover'">
                                        </div>
                                        <div class="w-full">
                                            <div class="text-lg">{{ $server['name'] }}</div>
                                            <div class="text-xs transition text-muted">{{ $server['platform'] . '-' . $server['device'] }} - {{ $server['publicAddress'] }}</div>
                                        </div>
                                        <div class="flex-center">
                                            <svg class="w-8 h-8 transition ms-3 text-muted" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="px-5 flex-center" x-show="open" x-cloak x-transition.in>
                                        <div class="p-0 border-t-0 rounded-t-none panel">
                                            <ul class="divide-y" wire:loading.class="opacity-70">
                                                @foreach ($server['connections'] as $connection_key => $connection)
                                                    <li class="p-3 space-y-1 @if ($connection['online']) hover:panel-active hover:cursor-pointer @else hover:cursor-not-allowed @endif" @if ($connection['online']) wire:click="selectPlexConnection({{ $server_key }}, {{ $connection_key }})" @endif wire:loading.class="pointer-events-none hover:!cursor-wait">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex flex-col space-y-1 overflow-hidden">
                                                                <div class="text-[8px] text-muted">
                                                                    @if (!$connection['online'])
                                                                        <span class="px-2 py-0.5 bg-red-500/40 text-white rounded-lg">Unreachable</span>
                                                                    @endif
                                                                    @if ($connection['protocol'] == 'https')
                                                                        <span class="px-2 py-0.5 bg-green-500/30 text-white rounded-lg">SSL</span>
                                                                    @endif
                                                                    @if ($connection['local'])
                                                                        <span class="px-2 py-0.5 bg-gray-700/40 rounded-lg">Local</span>
                                                                    @else
                                                                        <span class="px-2 py-0.5 bg-gray-700/40 rounded-lg">Remote</span>
                                                                    @endif
                                                                    @if ($connection['IPv6'])
                                                                        <span class="px-2 py-0.5 bg-gray-700/40 rounded-lg">IPV6</span>
                                                                    @endif
                                                                </div>
                                                                <div class="truncate @if (!$connection['online']) text-muted-dark @endif">
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
                                <div class="flex space-x-5 panel hover:panel-hover group" :class="open && 'panel-active'" x-on:click="open = !open">
                                    <div>
                                        <div class="flex-center w-[40px] h-[40px] panel p-0" :class="open && 'panel-hover'">
                                            <svg class="w-8 h-8 transition text-muted-dark" :class="open && '!text-muted'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 15l6 -6" />
                                                <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="text-lg">Custom Connection</div>
                                        <div class="text-xs transition text-muted">Enter the connection details manually</div>
                                    </div>
                                    <div class="flex-center">
                                        <svg class="w-8 h-8 transition ms-3 text-muted" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 6l6 6l-6 6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="px-5 flex-center" x-show="open" x-cloak x-transition.in>
                                    <div class="border-t-0 rounded-t-none panel">
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
                            </li>
                        @else
                            <li class="flex-col space-y-3 panel flex-center">
                                {{-- <div class="px-5 py-3 text-sm text-center border rounded-lg bg-gray-900/30 border-gray-700/40 text-muted opacity-60"> --}}
                                <svg class="w-16 h-16 opacity-20 text-muted" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12h-6a3 3 0 0 1 -3 -3v-2c0 -1.083 .574 -2.033 1.435 -2.56m3.565 -.44h10a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-2" />
                                    <path d="M16 12h2a3 3 0 0 1 3 3v2m-1.448 2.568a2.986 2.986 0 0 1 -1.552 .432h-12a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h6" />
                                    <path d="M7 8v.01" />
                                    <path d="M7 16v.01" />
                                    <path d="M3 3l18 18" />
                                </svg>
                                <div class="flex flex-col max-w-sm space-y-5 select-text">
                                    <div class="text-center text-muted">No server(s) found...</div>
                                    <div class="text-sm text-center text-muted-dark">Plex said that you do not have any servers associated with your Plex account. Refresh the page to check again.</div>
                                    <div class="text-sm text-center text-muted-dark">If you connected {{ config('app.name') }} with the wrong Plex account, you can <a class="text-link" href="#" wire:click="resetPlexAuth">sign out</a> to connect a different account.</div>
                                    <div class="text-sm text-center text-muted-dark">If you can not resolve the issue, check out <a class="text-link" href="https://support.plex.tv/articles/204604227-why-can-t-the-plex-app-find-or-connect-to-my-plex-media-server/" target="_blank">this article</a> for some helpful information, just pretend that "Plex App" is {{ config('app.name') }}.</div>
                                </div>
                                {{-- </div> --}}
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="text-center card-padding">
        <p class="text-sub">The server that you select must be online and accessible by {{ config('app.name') }}. If a server is <span class="px-2 text-[8px] py-0.5 bg-red-500/40 text-white rounded-lg">Unreachable</span>, you can refresh the page to check again. Remote hostnames may be unreachable due to the incorrect port number. To resolve this, you can use a custom connection and set the correct port number.</p>
    </div>
</div>
