<div>
    <x-page-title title="Minhas Filas"/>

    <div class="mt-2 p-6 flex flex-col justify-between gap-3">
        @foreach($queues as $queue)
            <a href="{{route('queue.position', ['id'=>$queue->id])}}" wire:navigate
                class="flex justify-between items-center bg-primary p-4 rounded-lg shadow-md">
                <div class="text-gray-300 font-bold antialiased text-2xl">
                    {{$queue->name}}
                </div>
                <div
                    class="text-gray-300 font-bold antialiased text-3xl pe-3">
                    1º
                </div>
            </a>
        @endforeach
    </div>
</div>
