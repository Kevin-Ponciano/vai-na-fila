@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

<x-navbar title="Bem vindo" :subtitle="auth()->user()->supermarket->name">
    <x-slot:imageProfile>
        <button data-dropdown-toggle="dropdownProfile" data-dropdown-placement="bottom-start"
                class="font-medium rounded-lg text-sm text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
            <img
                src="{{asset('assets/img/carone.png')}}"
                alt="Logo" class="h-12 w-12 rounded-full object-cover border-2 border-white">
        </button>

        <!-- Dropdown menu -->
        <div id="dropdownProfile"
             class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-primary text-center dark:text-gray-200">
                @if($isAdmin)
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
                @endif
                <li>
                    <a href="{{route('logout')}}"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        Sair
                    </a>
                </li>
            </ul>
        </div>
    </x-slot:imageProfile>
</x-navbar>
