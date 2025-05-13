@php
    use App\Enums\QueueTicketStatus;
@endphp
<div>
    <script>
        $(document).ready(() => {
            Echo.private('client.{{ auth()->id() }}.call')
                .listen('.ticket.updated', ({ticket}) => {
                    const {id, status: s, ticket_number: n, queue: {name: q}} = ticket;

                    switch (s) {
                        case '{{ QueueTicketStatus::CALLING->value }}':
                            notifyCalling(id, n, q);
                            break;
                        case '{{ QueueTicketStatus::EXPIRED->value }}':
                            notifyExpired(id, n, q);
                            break;
                        case '{{ QueueTicketStatus::IN_SERVICE->value }}':
                        case '{{ QueueTicketStatus::CALLED->value }}':
                        case '{{ QueueTicketStatus::WAITING->value }}':
                            window.location.href = '{{ route('my-queues') }}';
                            break;
                    }
                });

            /* ───────────── Helpers ───────────── */
            const buildUrl = (route, id) =>
                route.replace('PLACEHOLDER', id);

            const notifyCalling = (id, num, queue) => {
                const url = buildUrl("{{ route('queue.calling', ['id' => 'PLACEHOLDER']) }}", id);

                // Se a aba está visível, leva o usuário direto
                if (document.visibilityState === 'visible') {
                    window.location.href = url;
                    return;
                }

                const n = new Notification('Fila Digital - Sua Vez', {
                    body: `A sua vez chegou! ${num} - ${queue}`,
                    icon: '{{ asset('assets/img/logo.svg') }}',
                    data: {url}          // salva a URL desejada
                });

                n.onclick = e => {
                    e.preventDefault();     // impede nova aba no Firefox
                    n.close();
                    window.focus();
                    window.location.href = n.data.url;
                };
            };

            const notifyExpired = (id, num, queue) => {
                const url = "{{ route('my-queues') }}";

                if (document.visibilityState === 'visible') {
                    window.location.href = url;
                    return;
                }

                const n = new Notification('Fila Digital - Senha Expirada', {
                    body: `Você não compareceu na sua vez! ${num} - ${queue}`,
                    icon: '{{ asset('assets/img/logo.svg') }}',
                    data: {url}
                });

                n.onclick = e => {
                    e.preventDefault();
                    n.close();
                    window.focus();
                    window.location.href = n.data.url;
                };
            };
        });
    </script>

</div>
