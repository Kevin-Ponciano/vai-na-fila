@php
    use App\Enums\QueueTicketStatus;
@endphp
<div>
    <script>
        $(document).ready(function () {
            Echo.private('client.{{ auth()->id()}}.call')
                .listen('.ticket.updated', function (payload) {
                    const id = payload.ticket.id;
                    const ticketStatus = payload.ticket.status;
                    const ticketNumber = payload.ticket.ticket_number;
                    const queueName = payload.ticket.queue.name;

                    switch (ticketStatus) {
                        case '{{QueueTicketStatus::CALLING->value}}':
                            calling(id, ticketNumber, queueName);
                            break;
                        case '{{QueueTicketStatus::EXPIRED->value}}':
                            expired(id, ticketNumber, queueName);
                            break;
                        case '{{QueueTicketStatus::IN_SERVICE->value}}':
                            window.location.href = '{{ route('my-queues') }}';
                            break
                        case '{{QueueTicketStatus::CALLED->value}}':
                            window.location.href = '{{ route('my-queues') }}';
                            break
                    }
                });

            const calling = (id, ticketNumber, queueName) => {
                const notification = new Notification('Fila Digital - Sua Vez', {
                    body: `A sua vez chegou! ${ticketNumber} - ${queueName}`,
                    icon: '{{ asset('assets/img/logo.svg') }}'
                });

                notification.onclick = function () {
                    window.focus();
                    window.location.href = "{{ route('queue.calling', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);
                };

                //window.location.href = '{{ route('queue.calling', ['id' => 'PLACEHOLDER']) }}'.replace('PLACEHOLDER', id);
            }

            const expired = (id, ticketNumber, queueName) => {
                const notification = new Notification('Fila Digital - Senha Expirada', {
                    body: `Você não compareceu na sua vez! ${ticketNumber} - ${queueName}`,
                    icon: '{{ asset('assets/img/logo.svg') }}'
                });

                notification.onclick = function () {
                    window.focus();
                    window.location.href = "{{ route('my-queues') }}";
                };

                window.location.href = '{{ route('my-queues') }}';
            }
        });
    </script>
</div>
