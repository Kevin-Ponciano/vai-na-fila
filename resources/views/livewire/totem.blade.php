<div>
    <!-- Tela principal -->
    <div id="tela-inicial" class="flex flex-col min-h-screen bg-blue-100 text-center">
        <!-- Logo no topo -->
        <div class="pt-6 flex justify-center">
            <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[15rem]" />
        </div>

        <!-- Conteúdo centralizado -->
        <div class="flex-grow flex flex-col items-center justify-center">
            <img src="{{asset('assets/img/qrcode.png')}}" alt="QR Code" class="w-[30rem] mb-6" />
            <h2 class="text-xl font-semibold text-blue-800 mb-4">Retire sua senha:</h2>
            <div class="space-y-4 w-full max-w-xs text-[1.5rem]">
                <button onclick="mostrarTelaCarregando()"
                        class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">Geral
                </button>
                <button onclick="mostrarTelaCarregando()"
                        class="w-full bg-blue-700 text-white font-bold py-2 rounded-lg hover:bg-blue-800">Preferencial
                </button>
            </div>
        </div>
    </div>

    <!-- Tela de carregando -->
    <div id="tela-carregando"
         class="hidden flex flex-col items-center justify-center min-h-screen bg-blue-100 text-center">
        <img src="{{asset('assets/img/logo_nome.svg')}}" alt="Fila Digital" class="w-[15rem] mb-6" />
        <h2 class="text-6xl font-bold text-blue-800">Aguarde!<br>Imprimindo...</h2>
    </div>

    <script>
        function mostrarTelaCarregando() {
            document.getElementById('tela-inicial').classList.add('hidden');
            document.getElementById('tela-carregando').classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('tela-carregando').classList.add('hidden');
                document.getElementById('tela-inicial').classList.remove('hidden');
            }, 3000);
        }
    </script>
</div>
