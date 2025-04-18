<div class="bg-primary h-screen flex flex-col justify-center items-center">
    <div class="text-[9rem] text-[#ffffff] font-bold text-center">SENHA: <span
            id="senha">{{$ticket->ticket_number}}</span></div>


    <a href="{{route('queues.show', ['id'=>$queue->id])}}" wire:navigate
       class="text-white fixed start-6 bottom-[5rem] group bg-secondary p-3 rounded-full shadow-lg hover:bg-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-device-tv">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
            <path d="M16 3l-4 4l-4 -4"/>
        </svg>
    </a>
    <script>
        $(document).ready(function () {
            document.documentElement.requestFullscreen()
        });
    </script>
</div>
