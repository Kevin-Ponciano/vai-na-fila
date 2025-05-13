{{-- resources/views/livewire/client/queue-calling.blade.php --}}
<div
    x-data="countdown({{ $timeLeft }})" {{-- passa timeLeft para Alpine --}}
x-init="start()"
    class="flex flex-col min-h-screen bg-[#54EA14] text-center
           bg-gradient-to-b from-blue-50 to-transparent">

    {{-- topo / logo --}}
    <div class="pt-6 flex justify-center">
        <img src="{{ asset('assets/img/logo_nome.svg') }}" alt="Fila Digital" class="w-[15rem]"/>
    </div>

    {{-- corpo --}}
    <div class="flex-grow flex flex-col items-center justify-around">

        {{-- aviso de chamada --}}
        <div class="mb-[3rem] text-blue-800">
            <div class="text-5xl mb-6 mx-3 font-bold animate-pulse">
                CHEGOU A SUA VEZ!
            </div>
            <div class="text-4xl font-bold">
                {{ $ticket->ticket_number }}
            </div>
        </div>
        {{-- fila / botão --}}
        <div class="text-xl font-semibold text-blue-600 mb-[6rem]">
            <span>Fila: </span>
            <span class="text-2xl font-bold">{{ $ticket->queue->name }}</span>
        </div>
        {{-- cronômetro --}}
        <div class="text-sm font-bold mb-6" :class="{'text-red-600': remainingTime < 60}"
             x-show="remainingTime !== null">
            Expira em:
            <span x-text="formatTime(remainingTime)"></span>
        </div>
        <a href="{{ route('my-queues') }}">
            <x-button class="w-[8rem] text-white font-bold py-2 rounded-lg">
                Voltar
            </x-button>
        </a>
    </div>

    @livewire('echo-listen')
</div>


<script>
function countdown(initial) {
    return {
        remainingTime: Math.floor(initial) * (-1), // ← agora sempre inteiro
        timer: null,

        start() {
            this.tick();
            this.timer = setInterval(() => this.tick(), 1000);
        },

        tick() {
            if (this.remainingTime > 0) {
                this.remainingTime = Math.floor(this.remainingTime - 1); // ↓ int
            } else {
                this.remainingTime = 0;
                clearInterval(this.timer);
                this.timer = null;
            }
        },

        formatTime(sec) {
            sec = Math.floor(sec);            // ← agora sempre inteiro
            const m = String(Math.floor(sec / 60)).padStart(2, '0');
            const s = String(sec % 60).padStart(2, '0');
            return `${m}:${s}`;
        }
    }
}

document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', countdown);
});
</script>
