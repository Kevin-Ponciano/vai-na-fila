<div>
    <x-page-title title="Filas Disponíveis"/>
    <div class="mt-6 p-6">
        <div class="flex flex-col justify-between gap-6">
           @forelse($queues as $queue)
               <a href="{{route('queues.show', $queue->id)}}" wire:navigate
                       class="rounded rounded-lg p-2 grip items-center justify-between bg-white hover:bg-gray-300">
                   <img src="{{asset('assets/img/padaria_cropped.png')}}" alt="Logo"
                        class="object-cover w-full h-20 rounded">
                   <span class="text-secondary font-bold text-xl flex ps-3"
                   >{{$queue->name}}</span>
               </a>
        @empty
            <div class="w-full text-center">
                <p class="text-gray-500">Nenhuma fila disponível</p>
            </div>
        @endforelse
        </div>
    </div>
</div>



