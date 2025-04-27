{{-- resources/views/livewire/totem.blade.php --}}
<div
    x-data="qrComponent()"
    x-init="init()"
    class="flex flex-col min-h-screen bg-blue-100 text-center"
>
    <!-- ─────────── TELA INICIAL ─────────── -->
    <div id="tela-inicial" class="flex-grow flex flex-col">
        <div class="pt-6 flex justify-center">
            <img src="{{ asset('assets/img/logo_nome.svg') }}"
                 alt="Fila Digital" class="w-[15rem]"/>
        </div>

        <!-- Botões só aparecem se nada foi escolhido -->
        <template x-if="!prioritySelected">
            <div class="flex-grow flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold text-blue-800 mb-4">Retire sua senha:</h2>

                <div class="space-y-4 w-full max-w-xs text-[1.5rem]">
                    <button
                        @click="$wire.selectedQueue('normal')"
                        class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">
                        Geral
                    </button>

                    <button
                        @click="$wire.selectedQueue('priority')"
                        class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">
                        Preferencial
                    </button>
                </div>
            </div>
        </template>

        <!-- ─────────── TELA DO QR ─────────── -->
        <template x-if="prioritySelected && !loading">
            <div class="flex-grow flex flex-col items-center justify-center">
                <!-- Livewire não toca aqui -->
                <div
                    x-ref="canvas"
                    wire:ignore
                    class="w-[30rem] h-[30rem] bg-white rounded-lg shadow-lg flex items-center justify-center mb-6">
                </div>

                <h2 x-text="prioritySelected"
                    class="text-xl font-semibold text-blue-800 mb-4"></h2>

                <button
                    @click="print()"
                    class="w-full max-w-xs bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">
                    Imprimir
                </button>

                <button
                    @click="$wire.resetQueue()"
                    class="w-[8rem] bg-red-700 text-white font-bold py-2 rounded-lg hover:bg-red-800 mt-4">
                    Cancelar
                </button>
            </div>
        </template>

        <!-- ─────────── TELA DE CARREGANDO ─────────── -->
        <template x-if="loading">
            <div class="flex-grow flex flex-col items-center justify-center">
                <h2 class="text-6xl font-bold text-blue-800">
                    Aguarde!<br>Imprimindo...
                </h2>
            </div>
        </template>
    </div>
</div>


{{-- ─────────── SCRIPTS ─────────── --}}
<script src="{{ asset('assets/js/easy.qrcode.min.js') }}"></script>
<script>
    function qrComponent() {
        return {
            /* Two-way binding só para interface */
            prioritySelected: @entangle('prioritySelected'),
            loading: @entangle('loading'),

            baseOptions: {
                width: 450,
                height: 450,
                colorDark:  "#1c83f4",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H,
                logo: "{{ asset('assets/img/logo.svg') }}",
                logoWidth: 120,
                logoHeight: 120,
            },

            init() {
                /* Evento Livewire fornece a URL: */
                Livewire.on('generateQrCode', ({ url }) => {
                    this.draw(url);
                });

                Livewire.on('pr')
            },

            draw(text) {
                /* garante que o canvas exista */
                this.$nextTick(() => {
                    this.$refs.canvas.innerHTML = '';
                    new QRCode(this.$refs.canvas, { ...this.baseOptions, text });
                });
            },

            print() {
                this.loading = true;
                this.$wire.printQrCode();
            }
        }
    }

    document.addEventListener('alpine:init', () =>
        Alpine.data('qrComponent', qrComponent)
    );
</script>
