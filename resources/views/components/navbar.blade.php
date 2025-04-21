<nav class="sm:bg-secondary dark:bg-gray-900 px-2 sticky top-0 z-50">
    <header class="bg-secondary w-full px-4 text-white flex justify-between items-center">
        <div>
            @isset($title)
                <p class="antialiased text-sm text-white">{{$title}}</p>
                <div class="antialiased font-bold text-white">{{$subtitle}}</div>
            @endisset
        </div>
        @persist('dropdown')
        <div class="px-5 py-2.5">
            {{$imageProfile}}
        </div>
        @endpersist
    </header>
</nav>

