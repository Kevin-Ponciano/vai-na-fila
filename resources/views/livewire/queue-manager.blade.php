<div>
    <x-page-title title="Gerenciar Fila" :previus="route('queues')"/>
    <div class="mt-6 p-6">
        <div class="flex flex-col justify-between gap-6 justify-center items-center">
            <div>
                <span class="text-primary font-bold text-4xl">
                    Senha:
                </span>
                <div class="bg-gray-300 text-primary-dark font-bold rounded-lg p-2 text-5xl flex justify-center mt-7">
                    001
                </div>
            </div>
            <div>
                <x-button class="py-3 text-[1.5rem]"
                >Próximo
                </x-button>
            </div>
        </div>
        <div class="flex justify-center mt-10">
            <x-button class="bg-secondary active:bg-secondary-light">Anterior</x-button>
        </div>
    </div>
    <a
            class="text-white fixed start-6 bottom-[5rem] group bg-secondary p-3 rounded-full shadow-lg hover:bg-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-device-tv">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
            <path d="M16 3l-4 4l-4 -4"/>
        </svg>
    </a>
</div>
