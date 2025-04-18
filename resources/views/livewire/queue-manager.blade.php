<div>
    <!-- Título da página -->
    <x-page-title title="Gerenciar Fila" :previus="route('queues')"/>

    <!-- Painel principal -->
    <div class="mt-6 p-6">
        <div class="flex flex-col items-center gap-6">
            <!-- Senha atual -->
            <div class="text-center">
                <span class="text-primary font-bold text-4xl">Senha:</span>
                <div class="bg-gray-300 text-primary-dark font-bold rounded-lg p-2 text-5xl mt-7">
                    001
                </div>
            </div>

            <!-- Botão próximo -->
            <x-button class="py-3 text-[1.5rem]">Próximo</x-button>
        </div>

        <!-- Botão anterior -->
        <div class="flex justify-center mt-10">
            <x-button class="bg-secondary active:bg-secondary-light">Anterior</x-button>
        </div>
    </div>

    <!-- Leitor de QR Code (inicialmente oculto) -->
    <div class="mt-10 flex flex-col items-center">
        <x-button onclick="iniciarCamera()" class="mb-4 bg-blue-700 text-white">Abrir Câmera para Teste QR Code</x-button>
        <div id="reader" class="hidden w-full max-w-md mx-auto"></div>
        <div id="result" class="mt-4 text-center text-blue-800 font-bold text-lg"></div>
    </div>

    <!-- Atalhos flutuantes -->
    <a href="{{route('queues.totem', ['id'=>$queue->id])}}" wire:navigate
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

    <a href="{{route('queues.screen', ['id'=>$queue->id])}}" wire:navigate
       class="text-white fixed start-6 bottom-[5rem] bg-secondary p-3 rounded-full shadow-lg hover:bg-primary">
        <!-- Ícone de TV -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" fill="none" stroke="currentColor">
            <path stroke="none" d="M0 0h24v24H0z"/>
            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
            <path d="M16 3l-4 4l-4 -4"/>
        </svg>
    </a>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let qrScanner;

        function iniciarCamera() {
            const readerElement = document.getElementById('reader');
            readerElement.classList.remove('hidden');

            if (!qrScanner) {
                qrScanner = new Html5Qrcode("reader");

                qrScanner.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: 250
                    },
                    qrCodeMessage => {
                        document.getElementById('result').innerText = "QR Code detectado: " + qrCodeMessage;
                        qrScanner.stop();
                    },
                    errorMessage => {
                        console.warn(errorMessage);
                    }
                ).catch(err => {
                    console.error("Erro ao iniciar câmera:", err);
                });
            }
        }

        $(document).ready(function () {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
        });
    </script>
</div>
