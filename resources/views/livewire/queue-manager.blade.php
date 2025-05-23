@php use App\Enums\QueueTicketStatus; @endphp
<div>
    <!-- Título da página -->
    <x-page-title title="Gerenciar Fila" :previus="route('queues')"/>

    <!-- Painel principal -->
    <div class="mt-4 p-4">
        <div class="flex flex-col items-center gap-6">
            <!-- Senha atual -->
            <div class="text-center">
                <span class="text-primary font-bold text-4xl">Senha:</span>
                @if($currentTicket)
                    <div class="bg-gray-300 text-primary-dark font-bold rounded-lg p-2 text-5xl mt-7">
                        {{$currentTicket->ticket_number}}
                    </div>
                    <div
                        class="text-primary-dark font-bold rounded-lg text-1xl mt-1 @if($currentTicket->status == QueueTicketStatus::CALLING->value) animate-pulse @endif">
                        {{$currentTicket->status_name}}
                    </div>
                @else
                    <div class="bg-gray-300 text-primary-dark font-bold rounded-lg p-2 text-5xl mt-7">
                        000
                    </div>
                @endif
            </div>
            @if($currentTicket?->status == QueueTicketStatus::CALLING->value)
                <button type="button" wire:click="inServiceTicket"
                        class="focus:outline-none text-white bg-green-500 hover:bg-green-700 font-medium rounded-lg text-[1.5rem] px-4 py-2 me-2 mb-2">
                    Atender
                </button>
            @endif
        </div>

        <!-- Botão anterior -->
        <div class="flex justify-center mt-10 gap-4">
            <x-button wire:click="callPreviousTicket"
                      {{--                      wire:confirm="Você tem certeza que deseja voltar uma senha?"--}}
                      class="bg-secondary active:bg-secondary-light">Anterior
            </x-button>

            <x-button wire:click="callNextTicket" class="py-3 text-[1.5rem]">Próximo</x-button>

        </div>
    </div>

    <!-- Atalhos flutuantes -->
    <a href="{{route('queues.totem', ['id'=>$queue->id])}}"
       class="text-white fixed start-6 bottom-[10rem] bg-secondary p-3 rounded-full shadow-lg hover:bg-secondary">
        <!-- Ícone de totem -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" fill="none" stroke="currentColor">
            <path stroke="none" d="M0 0h24v24H0z"/>
            <path d="M5 3m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z"/>
            <path d="M9 7l6 0"/>
            <path d="M9 11l6 0"/>
            <path d="M9 15l4 0"/>
        </svg>
    </a>

    <a href="{{route('queues.screen', ['id'=>$queue->id])}}" wire:navigatemrs

       class="text-white fixed start-6 bottom-[5rem] bg-secondary p-3 rounded-full shadow-lg hover:bg-primary">
        <!-- Ícone de TV -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" fill="none" stroke="currentColor">
            <path stroke="none" d="M0 0h24v24H0z"/>
            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
            <path d="M16 3l-4 4l-4 -4"/>
        </svg>
    </a>

    <script>
        $(document).ready(function () {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }

            {{--Echo.private('queue.{{ $queue->id }}')--}}
            {{--    .listen('.ticket.generated', function (payload) {--}}
            {{--        @this.--}}
            {{--        call('$refresh')--}}
            {{--    });--}}
        });
    </script>
</div>
