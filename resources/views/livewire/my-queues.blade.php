@php
    use App\Enums\QueueTicketStatus;
@endphp
<div>
    <x-page-title title="Minhas Filas"/>

    <div class="mt-2 p-6 flex flex-col justify-between gap-3">
        @foreach($tickets as $ticket)
            <a href="{{route('queue.position', ['id'=> $ticket->id])}}" wire:navigate
               class="flex justify-between items-center p-4 rounded-lg shadow-md border-r-[1.3rem] border-primary
               @if($ticket->status === QueueTicketStatus::CALLING->value) animate-pulse bg-green-500
               @elseif($ticket->status === QueueTicketStatus::EXPIRED->value) bg-red-500
                @elseif($ticket->status === QueueTicketStatus::IN_SERVICE->value) bg-green-500
               @else bg-primary
                @endif">
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
