<div>
    <x-page-title :title="$queue->name"/>

    <div class="mt-2 p-6 flex flex-col justify-between h-[25rem]">
        <div>
            <div class="text-primary font-bold antialiased text-2xl text-center">
                Sua Senha:
            </div>
        </div>
        <div>
            <div class="text-primary font-bold antialiased text-[4rem] text-center">
                {{ $ticket->ticket_number }}
            </div>
        </div>
        <div class="text-center">
            <button wire:click="leaveQueue" wire:confirm="Você tem certeza que deseja desistir da fila?"
                class="w-[15rem] h-[3rem] font-bold text-red-600 hover:text-white border border-2 border-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg">
                Desistir da Fila
            </button>
        </div>
    </div>
</div>
