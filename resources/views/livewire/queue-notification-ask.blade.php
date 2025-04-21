<div class="flex flex-col min-h-screen bg-blue-100 text-center">
    <div class="pt-6 flex justify-center">
        <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[13rem]"/>
    </div>

    <h2 class="text-xl font-bold text-blue-800 px-3">Deseja ser notificado da posição da sua fila por WhatsApp ou
        SMS? </h2>

    <div class="flex-grow flex flex-col items-center justify-center">
        <div class="flex flex-col w-full max-w-xs">
            <a href="{{route('phone-number.register')}}" wire:navigate class="mb-4">
                <x-button class="w-[75%] text-[19px]">
                    Sim
                </x-button>
            </a>
            <a href="{{route('queue.position',['id'=>1])}}" wire:navigate>
                <x-danger-button class="w-[75%]">
                    Não, vou vigiar no aplicativo
                </x-danger-button>
            </a>
        </div>
    </div>
</div>
