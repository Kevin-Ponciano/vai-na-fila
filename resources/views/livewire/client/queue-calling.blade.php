<div class="flex flex-col min-h-screen bg-[#54EA14] text-center bg-gradient-to-b from-blue-50 to-transparent">
    <div id="tela-inicial" class="flex-grow flex flex-col">
        <div class="pt-6 flex justify-center">
            <img src="{{ asset('assets/img/logo_nome.svg') }}"
                 alt="Fila Digital" class="w-[15rem]"/>
        </div>
        <div class="flex-grow flex-col items-center justify-around">

            <!-- Senha -->
            <div class="mb-[3rem]">
                <div class="text-5xl mb-6 mx-3 font-bold text-blue-800 animate-pulse">
                    CHEGOU A SUA VEZ!
                </div>
                <div class="text-4xl font-bold text-blue-800">
                    {{$ticket->ticket_number}}
                </div>
            </div>

            <div class="text-xl font-semibold text-blue-600 mb-[6rem]">
                <span>Fila: </span>
                <span class="text-2xl font-bold">{{$ticket->queue->name}}</span>
            </div>

            <a href="{{route('my-queues')}}">
                <x-button class="w-[8rem] text-white font-bold py-2 rounded-lg">
                    Voltar
                </x-button>
            </a>
        </div>
    </div>

</div>
