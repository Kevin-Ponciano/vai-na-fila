<div>
    <x-page-title title="Gerar Relatório"/>
    <div class="mt-6 p-6">
        <div class="flex flex-col justify-between gap-4">
            @forelse($months as $month)
                <x-button 
                    wire:click="downloadReport('{{ $month['year_month'] }}')"
                    class="text-center text-[1rem] rounded cursor-pointer">
                    {{ $month['name'] }}
                </x-button>
            @empty
                <div class="w-full text-center">
                    <p class="text-gray-500">Nenhum relatório disponível</p>
                </div>
            @endforelse
        </div>
    </div>
</div>