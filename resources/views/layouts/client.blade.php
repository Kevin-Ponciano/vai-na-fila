<x-app-layout>
    <x-navbar-client/>
    <main class="pb-[3.5rem]">
        {{ $slot }}
    </main>
    <x-footer-client/>
    <script>
        $(document).ready(function () {
            // 2️⃣ registra o listener de broadcast via Echo
            Echo.private('client.{{ auth()->id()}}.call')
                .listen('.ticket.called', function (payload) {
                    const id = payload.ticket.id;
                    const ticketNumber = payload.ticket.ticket_number;
                    const queueName = payload.ticket.queue.name;

                    const notification = new Notification('Fila Digital - Vai na fila', {
                        body: `A sua vez chegou! ${ticketNumber} - ${queueName}`,
                        icon: '{{ asset('assets/img/logo.svg') }}'
                    });

                    notification.onclick = function () {
                        window.focus();
                        window.location.href = "{{ route('queue.calling', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);
                    };


                    //window.location.href = '{{ str_replace('PLACEHOLDER', '', route('queue.calling', ['id' => 'PLACEHOLDER'])) }}' + id;
                });
        });
    </script>
</x-app-layout>
