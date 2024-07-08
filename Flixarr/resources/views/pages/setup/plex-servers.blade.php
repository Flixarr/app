<div class="h-full text-center" wire:init="load">
    <div class="card-container">
        <div class="card">
            <div>
                <h2 class="card-title">Connect your Plex Server</h2>
                <p class="card-desc">Next, select which server connection you would like to use.</p>
            </div>

            <div wire:loading>
                <div class="flex justify-center">
                    <svg class="w-[37px] h-[37px] text-blue-100 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <div class="">
                <ul class="flex flex-col space-y-5">
                    @if ($servers_loaded)
                        @if (count($servers) > 0)
                            @foreach ($servers as $server_key => $server)
                                <li x-data="{ open: false }" x-on:click.away="open = false">
                                    <div class="inline-flex items-center justify-between w-full p-5 space-x-5 text-left transition-colors border rounded-lg cursor-pointer hover:shadow-xl group hover:border-gray-700 border-gray-700/40 bg-gray-900/30" for="server-{{ $server_key }}" :class="open && '!bg-gray-800/70 border-gray-700 shadow-xl'" x-on:click="open = !open">
                                        <div>
                                            <img class="w-16 transition border rounded-lg group-hover:border-gray-700 aspect-square border-gray-700/40" src="https://preview.redd.it/new-plex-logo-v0-5x93lknmuaw81.jpg?auto=webp&s=a8edd33ea3d1f38929c7917abea05291ad49f528" alt="" :class="open && 'border-gray-700'">
                                        </div>
                                        <div class="block w-full">
                                            <div class="w-full text-lg">{{ $server['name'] }}</div>
                                            <div class="w-full text-xs transition-colors text-muted group-hover:text-muted-light" :class="open && 'text-muted-light'">{{ $server['platform'] . '-' . $server['device'] }} - {{ $server['publicAddress'] }}</div>
                                        </div>
                                        <div class="flex items-center text-muted group-hover:text-white" :class="open && '!text-white'">
                                            <svg class="w-8 h-8 transition ms-3" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex justify-center px-5 text-left" x-show="open" x-cloak x-transition.in>
                                        <div class="w-full border border-t-0 rounded-b-lg bg-gray-900/40 border-gray-700/40">
                                            <ul class="divide-y divide-gray-700/40">
                                                @foreach ($server['connections'] as $connection_key => $connection)
                                                    <li class="flex flex-col p-3 space-y-1 @if ($connection['online']) hover:bg-gray-800/60 hover:cursor-pointer @else hover:cursor-not-allowed @endif" wire:click="selectConnection({{ $server_key }}, {{ $connection_key }})">
                                                        <div class="text-[9px] text-muted">
                                                            @if (!$connection['online'])
                                                                <span class="px-2 py-0.5 bg-red-500/40 text-white rounded-lg">Unreachable</span>
                                                            @endif
                                                            @if ($connection['protocol'] == 'https')
                                                                <span class="px-2 py-0.5 bg-green-500/30 text-white rounded-lg">https</span>
                                                            @endif
                                                            @if ($connection['local'])
                                                                <span class="px-2 py-0.5 bg-gray-700/40 rounded-lg">local</span>
                                                            @else
                                                                <span class="px-2 py-0.5 bg-gray-700/40 rounded-lg">remote</span>
                                                            @endif
                                                            @if ($connection['IPv6'])
                                                                <span class="px-2 py-0.5 bg-gray-700/40 rounded-lg">ipv6</span>
                                                            @endif
                                                        </div>
                                                        <div class="truncate @if (!$connection['online']) !text-muted-dark @endif">
                                                            {{ $connection['address'] }} <span class="text-xs text-muted @if (!$connection['online']) !text-muted-dark @endif">: {{ $connection['port'] }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <div class="px-5 py-3 text-sm text-left border rounded-lg bg-gray-900/30 border-gray-700/40 text-muted opacity-60">
                                    no servers</div>
                            </li>
                        @endif
                        <li class="text-muted">
                            <hr class="w-1/2 h-0.5 mx-auto border-0 bg-gray-700/50">
                        </li>
                        <li x-data="{ open: false }">
                            <div class="inline-flex items-center justify-between w-full p-5 space-x-5 text-left transition-colors border rounded-lg cursor-pointer hover:shadow-xl group hover:border-gray-700 border-gray-700/40 bg-gray-900/30" :class="open && '!bg-gray-800/70 border-gray-700'" x-on:click="open = !open">
                                <div class="block w-full">
                                    <div class="w-full text-lg">Custom Connection</div>
                                    <div class="w-full text-xs transition-colors text-muted group-hover:text-muted-light" :class="open && 'text-muted-light'">Enter the connection details manually</div>
                                </div>
                                <div class="flex items-center text-muted group-hover:text-white" :class="open && '!text-white'">
                                    <svg class="w-8 h-8 transition ms-3" :class="open && 'rotate-90'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 6l6 6l-6 6" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-center px-5" x-show="open" x-cloak x-transition.in>
                                <div class="w-full p-3 text-left border border-t-0 rounded-b-lg bg-gray-900/40 border-gray-700/40">
                                    <div class="grid grid-cols-3 gap-3">

                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium leading-6 text-muted" for="email">Hostname / IP Address:</label>
                                            <div class="mt-2">
                                                <input class="block w-full py-2 border-0 rounded-lg shadow-sm bg-gray-800/50 ring-1 ring-inset ring-gray-700/40 placeholder:text-muted/50 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" id="server" name="server" type="text" placeholder="192.168.1.4">
                                            </div>
                                        </div>
                                        <div class="col-span-1">
                                            <label class="block text-sm font-medium leading-6 text-muted" for="email">Port:</label>
                                            <div class="mt-2">
                                                <input class="block w-full py-2 border-0 rounded-lg shadow-sm bg-gray-800/50 ring-1 ring-inset ring-gray-700/40 placeholder:text-muted/50 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" id="server" name="server" type="text" placeholder="32400">
                                            </div>
                                        </div>
                                        <div class="col-span-full">
                                            <div class="flex items-center">
                                                <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" id="link-checkbox" type="checkbox" value="">
                                                <label class="text-sm font-medium text-gray-900 ms-2 dark:text-gray-300" for="link-checkbox">Use HTTPS</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="card-spacing">
        <p class="text-sub">Connecting your Plex account allows Flixarr to communicate with your Plex Media Server. Your login details are never visible to Flixarr. More information can be found <a class="text-link" href="#" target="_blank">here</a>.</p>
    </div>
</div>
