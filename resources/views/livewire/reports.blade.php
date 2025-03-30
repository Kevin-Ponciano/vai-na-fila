<div>
    <x-page-title title="Gerar Relatório"/>
    <div class="mt-6 p-6">
        <div class="flex flex-col justify-between gap-4">
           @forelse($reports as $report)
               <x-button class="text-center text-[1rem] rounded">
                   {{$report->name}}
               </x-button>
        @empty
            <div class="w-full text-center">
                <p class="text-gray-500">Nenhum relatório disponível</p>
            </div>
        @endforelse
        </div>
    </div>
</div>
