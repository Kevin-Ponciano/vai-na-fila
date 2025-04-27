{{-- resources/views/livewire/queue-qr-reader.blade.php --}}
<div x-data="qrReader()" x-init="init()">
    <x-page-title title="Ler QR Code da Fila"/>

    <div class="mt-2 p-6 flex flex-col justify-between h-[25rem]">

        <!-- Scanner + mensagem -->
        <div class="mt-10 flex flex-col items-center">
            <div id="reader" x-ref="reader"
                 class="hidden w-full max-w-md mx-auto"></div>

            <div x-show="message"
                 x-text="message"
                 class="mt-4 text-center font-bold text-lg"
                 :class="{
                     'text-green-600': messageType === 'success',
                     'text-red-600'  : messageType === 'error',
                     'text-blue-800' : messageType === 'info'
                 }">
            </div>
        </div>

        <!-- Botões ---------------------------------------------------------->
        <div class="text-center">
            <x-button x-show="!scanning && !busy"
                      @click="startCamera"
                      class="mb-4 bg-blue-700 text-white">
                <span x-text="openBtnLabel"></span>
            </x-button>

            <x-button x-show="scanning && !busy"
                      @click="stopCamera"
                      class="mb-4 bg-red-700 text-white">
                Fechar Câmera
            </x-button>
        </div>


    </div>
    @persist('qrcodejs')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qrReader', () => ({

                /* ---------------- state ---------------- */
                scanner: null,
                scanning: false,
                busy: false,          // ← evita transição dupla
                readingReady: false,
                message: '',
                messageType: 'info',
                lastRejected: null,
                openBtnLabel: 'Abrir Câmera',

                /* --------------- life-cycle ------------ */
                init() {
                    Livewire.on('qr-validation', async ({validation, message, qr_code}) => {
                        this.message = message;
                        this.messageType = validation ? 'success' : 'error';

                        if (validation) {
                            await this.stopCamera();
                            window.location.href = qr_code;
                        } else {
                            this.lastRejected = qr_code;
                            await this.stopCamera();          // fecha vídeo sem erro
                            this.openBtnLabel = 'Tentar novamente';
                        }
                    });

                    window.addEventListener('livewire:navigating', () => this.stopCamera());
                },

                /* ---------------- actions --------------- */
                async startCamera() {
                    if (this.scanning || this.busy) return;
                    this.busy = true;

                    /* cria sempre uma nova instância limpa */
                    this.scanner = new Html5Qrcode(this.$refs.reader.id);

                    try {
                        this.$refs.reader.classList.remove('hidden');
                        this.message = 'Procurando QR Code...';
                        this.messageType = 'info';
                        this.openBtnLabel = 'Abrir Câmera';

                        await this.scanner.start(
                            {facingMode: 'environment'},
                            {fps: 10, qrbox: 250},
                            code => this.onDecode(code),
                            err => this.onDecodeError(err)
                        );

                        this.scanning = true;
                        this.readingReady = false;
                        setTimeout(() => (this.readingReady = true), 800);
                    } catch (e) {
                        console.error(e);
                        this.message = 'Erro ao acessar a câmera.';
                        this.messageType = 'error';
                        this.$refs.reader.classList.add('hidden');
                    } finally {
                        this.busy = false;
                    }
                },

                async stopCamera() {
                    if (!this.scanner || !this.scanning || this.busy) return;
                    this.busy = true;

                    try {
                        await this.scanner.stop();
                        this.scanner.clear();
                    } catch (e) {
                        console.warn('[QR] stop error:', e);
                    } finally {
                        this.scanning = false;
                        this.readingReady = false;
                        this.lastRejected = null;
                        this.$refs.reader.classList.add('hidden');
                        this.busy = false;
                    }
                },

                /* --------------- callbacks ------------- */
                onDecode(code) {
                    if (!this.readingReady) return;
                    if (code === this.lastRejected) return;

                    this.readingReady = false;
                    this.message = '⌛ Validando...';
                    this.messageType = 'info';

                    this.$wire.validateQr(code);
                },

                onDecodeError(err) {
                    // silencioso, só loga se quiser
                },
            }));
        });
    </script>
    @endpersist
</div>


