<div>
    <x-page-title title="Minhas Filas"/>

    <div class="mt-2 p-6 flex flex-col justify-between gap-3">
        @foreach($tickets as $ticket)
            <a href="{{route('queue.position', ['id'=> $ticket->id])}}" wire:navigate
               class="flex justify-between items-center bg-primary p-4 rounded-lg shadow-md">
                <div class="text-gray-300 font-bold antialiased text-2xl">
                    {{$ticket->queue->name}}
                </div>
                <div
                    class="text-gray-300 font-bold antialiased text-3xl pe-3">
                    {{$ticket->ticket_number}}
                </div>
            </a>
        @endforeach
    </div>
</div>
