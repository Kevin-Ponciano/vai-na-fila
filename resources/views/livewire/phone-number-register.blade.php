<div class="flex flex-col min-h-screen bg-blue-100 text-center">
    <div class="pt-6 flex justify-center">
        <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[13rem]"/>
    </div>

    <h2 class="text-xl font-bold text-blue-800 mb-4 px-3">Insira seu número de celular:</h2>

    <div class="flex flex-col items-center justify-center">
        <form wire:submit="save" class="flex flex-col w-full max-w-xs">
            <x-input type="text" wire:model="phoneNumber" x-mask="(99) 99999-9999" placeholder="(xx) xxxxx-xxxx" class="mb-4 text-center"/>
            <x-button class="w-[75%] text-[19px] mx-auto">
                Acessar
            </x-button>
        </form>
    </div>
</div>
