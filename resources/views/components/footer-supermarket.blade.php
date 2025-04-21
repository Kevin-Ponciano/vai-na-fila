<x-footer>
    <a class="text-xl text-white" href="{{route('queues')}}" wire:navigate wire:current="text-primary">
        <svg class="w-6 h-6 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
             width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd"
                  d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                  clip-rule="evenodd"/>
        </svg>
    </a>
    <a class="text-xl text-white" href="{{route('reports')}}" wire:navigate wire:current="text-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
             class="icon icon-tabler icons-tabler-filled icon-tabler-clipboard-data">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path
                d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.997 7.83a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1m3 3a1 1 0 0 0 -1 1v1a1 1 0 0 0 1 1l.117 -.007a1 1 0 0 0 .883 -.993v-1a1 1 0 0 0 -1 -1m3 -1a1 1 0 0 0 -1 1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0 -1 -1m-1 -12a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z"/>
        </svg>
    </a>
    <a class="text-xl text-white" href="{{route('users')}}" wire:navigate wire:current="text-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-users-group">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
            <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"/>
            <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
            <path d="M17 10h2a2 2 0 0 1 2 2v1"/>
            <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
            <path d="M3 13v-1a2 2 0 0 1 2 -2h2"/>
        </svg>
    </a>
</x-footer>
