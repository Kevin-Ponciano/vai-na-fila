<nav class="sm:bg-secondary dark:bg-gray-900 px-2 sticky top-0 z-50">
    <header class="bg-secondary w-full px-4 text-white flex justify-between items-center">
        <div>
            <p class="antialiased text-sm text-white">Bem-vindo</p>
            <div class="antialiased font-bold text-white">{{auth()->user()->supermarket->name}}</div>
        </div>
        @persist('dropdown')
        <button data-dropdown-toggle="dropdownProfile" data-dropdown-placement="bottom-start"
                class="font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            <img
                src="{{asset('assets/img/carone.png')}}"
                alt="Logo" class="h-12 w-12 rounded-full object-cover border-2 border-white">
        </button>

        <!-- Dropdown menu -->
        <div id="dropdownProfile"
             class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-primary text-center dark:text-gray-200">
                <li>
                    <a href="#"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                       aria-current="page"
                    >Perfil</a>
                </li>
                <hr class="text-primary-dark">
                <li>
                    <a href="#"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Configurações</a>
                </li>
                <hr class="text-primary-dark">
                <li>
                    <a href="{{route('logout')}}"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        Sair
                    </a>
                </li>
            </ul>
        </div>
        @endpersist
    </header>
</nav>

