<div class="px-3 py-2 bg-gray-900 border rounded-full shadow-2xl cursor-pointer group bg-gradient-to-r m-3 mb-0 tablet:mr-8" :class="{
    'from-blue-700/30 to-blue-950/5 to-80% border-gray-700/50': toast.type === 'info',
    'from-green-600/30 to-red-950/10 to-90% border-gray-700/50': toast.type === 'success',
    'from-yellow-500/30 to-red-950/10 to-80% border-gray-700/50': toast.type === 'warning',
    'from-red-700/30 to-red-950/10 to-80% border-gray-700/50': toast.type === 'danger',
    'from-gray-700/40 to-red-950/10 to-80% border-gray-700/50': toast.type === 'debug',
}">
    <div class="flex items-center space-x-4">
        <div class="mt-px">
            @include('tall-toasts::includes.icon')
        </div>
        <div class="w-full">
            {{-- Title --}}
            <div class="font-medium" x-html="toast.title" x-show="toast.title !== undefined" :class="{
                'text-blue-400': toast.type === 'info',

                'text-green-500': toast.type === 'success',
                'text-yellow-500': toast.type === 'warning',
                'text-red-500': toast.type === 'danger',
                'text-white': toast.type === 'debug',
            }"></div>
            {{-- Message --}}
            <div class="text-sm leading-5 text-white/80" x-show="toast.message !== undefined" x-html="toast.message"></div>
        </div>

        <button class="block">
            {{-- Close button --}}
            <svg class="w-5 h-5 group-hover:text-white text-muted-dark" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>
