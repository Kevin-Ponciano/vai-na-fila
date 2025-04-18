<div>
    <div id="tela-inicial" class="flex flex-col min-h-screen bg-blue-100 text-center">
        <div class="pt-6 flex justify-center">
            <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[15rem]" />
        </div>

        <div class="flex-grow flex flex-col items-center justify-center">
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Retire sua senha:</h2>
            <div class="space-y-4 w-full max-w-xs text-[1.5rem]">
                <button onclick="mostrarTelaQR('Geral')"
                        class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">Geral
                </button>
                <button onclick="mostrarTelaQR('Preferencial')"
                        class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">Preferencial
                </button>
            </div>
        </div>
    </div>

    <div id="tela-qr" class="hidden flex flex-col min-h-screen bg-blue-100 text-center">
        <div class="pt-6 flex justify-center">
            <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[15rem]" />
        </div>


        <div class="flex-grow flex flex-col items-center justify-center">
            <img id="qr-code" src="{{asset('assets/img/qrcode.png')}}" alt="QR Code" class="w-[30rem] mb-6" />
            <h2 id="tipo-senha" class="text-xl font-semibold text-blue-800 mb-4"></h2>
            <button onclick="imprimirQR()"
                    class="w-full max-w-xs bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">Imprimir
            </button>
        </div>
    </div>

    <!-- Tela de carregando -->
    <div id="tela-carregando"
         class="hidden flex flex-col items-center justify-center min-h-screen bg-blue-100 text-center">
        <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[15rem] mb-6" />
        <h2 class="text-6xl font-bold text-blue-800">Aguarde!<br>Imprimindo...</h2>
    </div>

    <script>
        function mostrarTelaQR(tipo) {
            document.getElementById('tela-inicial').classList.add('hidden');
            valor
            document.getElementById('tela-qr').classList.remove('hidden');
            document.getElementById('tipo-senha').textContent = `Senha ${tipo}`;
        }

        function imprimirQR() {
            document.getElementById('tela-qr').classList.add('hidden');
            document.getElementById('tela-carregando').classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('tela-carregando').classList.add('hidden');
                document.getElementById('tela-inicial').classList.remove('hidden');
            }, 3000);
        }
    </script>
</div>
