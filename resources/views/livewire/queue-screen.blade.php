<div class="bg-primary h-screen flex flex-col justify-center items-center">
    <div class="text-[9rem] text-white font-bold text-center transition-all duration-300">
        SENHA: <span id="senha" class="transition-all duration-300">{{ $ticket->ticket_number ?? '000' }}</span>
    </div>

    <a href="{{ route('queues.show', ['id'=> $queue->id]) }}" wire:navigate
       class="text-white fixed start-6 bottom-[5rem] group bg-secondary p-3 rounded-full shadow-lg hover:bg-secondary transition-colors duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-device-tv">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
            <path d="M16 3l-4 4l-4 -4"/>
        </svg>
    </a>
    <audio id="beep-sound" src="{{ asset('assets/sounds/soundscrate-censor-beep-short.mp3') }}" preload="auto"></audio>
    <script>
        $(document).ready(function () {
            // 1️⃣ fullscreen automático
            if (document.fullscreenEnabled) {
                document.documentElement.requestFullscreen().catch(err => {
                });
            }

            Echo.private('queue.{{ $queue->id }}')
                .listen('.ticket.called', function (payload) {
                    const senhaEl = $('#senha');
                    senhaEl.text(payload.ticket.ticket_number);

                    // Reproduz o som
                    const beepSound = document.getElementById('beep-sound');
                    beepSound.currentTime = 0; // Reinicia o som
                    beepSound.play().catch(err => {
                        console.error('Erro ao reproduzir o som:', err);
                    });

                    // Adiciona destaque temporário
                    senhaEl.addClass('text-yellow-300 scale-110 animate-ping');
                    // Remove destaque após animação
                    setTimeout(() => {
                        senhaEl.removeClass('text-yellow-300 scale-110 animate-ping');
                    }, 1000);
                });
        });
    </script>
</div>
