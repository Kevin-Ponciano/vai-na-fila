<div>
    <x-page-title title="Ler QR Code da Fila"/>

    <div class="mt-2 p-6 flex flex-col justify-between h-[25rem]">
        <div>
            <!-- Leitor de QR Code (inicialmente oculto) -->
            <div class="mt-10 flex flex-col items-center">
                <div id="reader" class="hidden w-full max-w-md mx-auto"></div>
                <div id="result" class="mt-4 text-center text-blue-800 font-bold text-lg"></div>
            </div>
        </div>
        <div class="text-center">
            <x-button id="open-can-btn" class="mb-4 bg-blue-700 text-white">
                Abrir Câmera
            </x-button>
        </div>
    </div>
</div>
<script src="https://unpkg.com/html5-qrcode"></script>
@script
<script>
    $(document).ready(function () {
        let qrScanner;
        let isScanning = false;
        let erroContador = 0;
        let leituraHabilitada = false;

        const openCameraButton = $('#open-can-btn');
        const closeCameraButtonId = 'close-can-btn';

        openCameraButton.on('click', function () {
            iniciarCamera();
        });

        function iniciarCamera() {
            const readerElement = document.getElementById('reader');
            const resultElement = document.getElementById('result');

            readerElement.classList.remove('hidden');
            resultElement.innerText = "Procurando QR Code...";

            if (isScanning) return;

            if (!qrScanner) {
                qrScanner = new Html5Qrcode("reader");
            }

            qrScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: 250
                },
                qrCodeMessage => {
                    if (!leituraHabilitada) return; // evita leitura logo no início

                    resultElement.innerText = "✅ QR Code detectado: " + qrCodeMessage;
                    // Livewire.dispatch('qrCodeDetected', { qrCode: qrCodeMessage });
                    pararScanner();
                },
                errorMessage => {
                    erroContador++;
                    if (erroContador % 50 === 0) {
                        console.warn("Erro de leitura:", errorMessage);
                        resultElement.innerText = "🔄 Tentando novamente...";
                    }
                }
            ).then(() => {
                isScanning = true;
                mostrarBotaoFechar();

                // ✅ Aguarda 800ms para ativar a leitura real (evita ler o frame inicial)
                leituraHabilitada = false;
                setTimeout(() => {
                    leituraHabilitada = true;
                    console.log("Leitura ativada.");
                }, 800);
            }).catch(err => {
                console.error("Erro ao iniciar câmera:", err);
            });
        }

        function pararScanner() {
            if (qrScanner && isScanning) {
                qrScanner.stop().then(() => {
                    isScanning = false;
                    qrScanner.clear();
                    leituraHabilitada = false;
                    esconderBotaoFechar();
                }).catch(err => {
                    console.warn("Erro ao parar scanner:", err);
                });
            }
        }

        function mostrarBotaoFechar() {
            if (!document.getElementById(closeCameraButtonId)) {
                const btn = document.createElement("button");
                btn.id = closeCameraButtonId;
                btn.className = "bg-red-600 text-white rounded rounded-md text-xs px-4 py-2 font-semibold";
                btn.innerText = "Fechar Câmera";
                btn.onclick = function () {
                    pararScanner();
                    const resultElement = document.getElementById('result');
                    resultElement.innerText = "Câmera fechada.";
                    btn.remove();
                };

                openCameraButton.parent().append(btn);
            }
        }

        function esconderBotaoFechar() {
            const btn = document.getElementById(closeCameraButtonId);
            if (btn) btn.remove();
        }

        // Fecha a câmera ao navegar com Livewire
        window.addEventListener('livewire:navigating', () => {
            pararScanner();
        });

        // Se retornar à página, reseta o scanner
        window.addEventListener('livewire:navigated', () => {
            if (document.getElementById('reader')) {
                pararScanner();
            }
        });
    });
</script>


@endscript
