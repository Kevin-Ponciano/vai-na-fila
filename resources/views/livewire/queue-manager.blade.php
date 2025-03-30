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
                >Próximo</x-button>
            </div>
        </div>
        <div class="flex justify-center mt-10">
            <x-button class="bg-secondary active:bg-secondary-light">Anterior</x-button>
        </div>
    </div>
</div>
