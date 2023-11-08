<div class="z-[5000] fixed top-0 w-full tablet:max-w-md tablet:right-0 tablet:top-auto" x-data='ToastComponent($wire)' @mouseleave="scheduleRemovalWithOlder()">
    <template x-for="toast in toasts.filter((a)=>a)" :key="toast.index">
        <div @mouseenter="cancelRemovalWithNewer(toast.index)" @mouseleave="scheduleRemovalWithOlder(toast.index)" @click="remove(toast.index)" x-show="toast.show===1" x-transition:enter="transform ease-out duration-500 transition" x-transition:enter-start="-translate-y-[2rem] opacity-0 sm:translate-y-0 sm:translate-x-[40rem]" x-transition:enter-end="translate-y-0 sm:translate-x-0 opacity-100" x-transition:leave="transform ease-in duration-500 transition" x-transition:leave-start="translate-y-0 opacity-30 sm:translate-x-0" x-transition:leave-end="-translate-y-[40rem] opacity-0 sm:translate-y-0 sm:translate-x-[80rem]" x-init="$nextTick(() => { toast.show = 1 })">
            @include('tall-toasts::includes.content')
        </div>
    </template>
</div>
