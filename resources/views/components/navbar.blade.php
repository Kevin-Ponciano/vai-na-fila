<nav class="sm:bg-secondary dark:bg-gray-900 px-2">
    <header class="bg-secondary w-full px-4 text-white flex justify-between items-center">
        <div>
            <p class="antialiased text-sm text-white">Bem-vindo</p>
            <div class="antialiased font-bold text-white">{{auth()->user()->supermarket->name}}</div>
        </div>
        @persist('dropdown')
        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                class="font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">


            <img
                src="https://s3-alpha-sig.figma.com/img/fd79/50e9/a98d30cb57cc258d9633c2b92a272ca7?Expires=1743379200&Key-Pair-Id=APKAQ4GOSFWCW27IBOMQ&Signature=Quh6zSPbqRKTGajEpkn~EkbXglnX3c2etentVaFuRFgI3PS~gnzoai3onGO0622F7eXQi9knLD3lBEGvN3~9w05ap1ENYEYfzMO68G85wgWZDCGr0KcOtX6pB1EDMfeGmZeCjpCbFmQcQ1HK30HvXrjlqKAy4wgwDIhoH8ishDxGKYa~fLJan3awN7aQPIqliTnFLaOUAzAZ3zcx6M7S2jfBaXbQ39EmD0ZhXHQJa8xmjnoZsQx6m32y3xFUm31BXg5eFaMtArEboR1SJg5I7HlddY0k-MxoSM~Cqo8Si66rgNH760KoDl6npKPHZXeynJLKR8-~HpbhnBzCKnpsew__"
                alt="Logo" class="h-12 w-12 rounded-full object-cover border-2 border-white">


        </button>

        <!-- Dropdown menu -->
        <div id="dropdown"
             class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                <li>
                    <a href="#"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                       aria-current="page"
                    >Perfil</a>
                </li>
                <li>
                    <a href="#"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Configurações</a>
                </li>
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
{{--    center screen image--}}
{{--<img src="{{asset('assets/img/filas.svg')}}" alt="Logo"--}}
{{--     class="absolute object-cover sm:top-[30%] sm:left-1/2 sm:transform sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-[75%] sm:z-[3] lg:hidden">--}}
