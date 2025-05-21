<?php

namespace App\Services;

use App\Models\QueueTicket;
use GuzzleHttp\Promise\PromiseInterface;
use Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

class EvolutionApiService
{
    protected string $serverUrl;
    protected string $key;
    protected string $instance;
    protected array $headers = [];
    protected int $delay = 3;

    public function __construct()
    {
        $this->serverUrl = config('services.evolution.server_url');
        $this->key = config('services.evolution.key');
        $this->instance = config('services.evolution.instance');

        $this->headers = [
            'Content-Type' => 'application/json',
            'apiKey' => $this->key
        ];
    }

    public function ticketStatusWaiting(string $number, QueueTicket $ticket): PromiseInterface|Response
    {
        $text = "*Vai na Fila!*\n\n" .
            "*Sua senha já está na fila!*\n\n" .
            "*A sua senha está na posição {$ticket->position}*\n" .
            "Senha: {$ticket->ticket_number}\n" .
            "Fila: {$ticket->queue->name}";

        $response = $this->sendPlainText($number, $text);
        if ($response->failed()) {
            throw new ConnectionException('Falha ao enviar mensagem: ' . $response->body());
        }
        return $response;
    }

    /**
     * @throws ConnectionException
     */
    public function sendPlainText(string $number, string $text): PromiseInterface|Response
    {
        $uri = $this->serverUrl . '/message/sendText/' . $this->instance;


        $data = [
            'number' => $this->prepareNumber($number),
            'text' => $text,
            'delay' => $this->delay,
        ];

        return $response = Http::withHeaders($this->headers)->post($uri, $data);
    }

    private function prepareNumber(string $number): string
    {
        // Remove all non-numeric characters
        $number = preg_replace('/\D/', '', $number);

        // Check if the number is already in the correct format
        if (strlen($number) === 11 && str_starts_with($number, '55')) {
            return $number;
        }

        // Add the country code (55 for Brazil)
        return '55' . $number;
    }

    public function ticketStatusCalling(string $number, QueueTicket $ticket): PromiseInterface|Response
    {
        $text = "*Vai na Fila!*\n\n" .
            "*Chegou a sua vez de ser atendido!*\n\n" .
            "Senha: {$ticket->ticket_number}\n" .
            "Fila: {$ticket->queue->name}";

        $response = $this->sendPlainText($number, $text);
        if ($response->failed()) {
            throw new ConnectionException('Falha ao enviar mensagem: ' . $response->body());
        }
        return $response;
    }

    public function ticketStatusExpired(string $number, QueueTicket $ticket): PromiseInterface|Response
    {
        $text = "*Vai na Fila!*\n\n" .
            "*A sua senha Expirou, você não chegou a tempo!*\n\n" .
            "Senha: {$ticket->ticket_number}\n" .
            "Fila: {$ticket->queue->name}";

        $response = $this->sendPlainText($number, $text);
        if ($response->failed()) {
            throw new ConnectionException('Falha ao enviar mensagem: ' . $response->body());
        }
        return $response;
    }

    public function ticketStatusPosition(string $number, QueueTicket $ticket): PromiseInterface|Response
    {
        $text = "*Vai na Fila!*\n\n" .
            "*A sua Vez está chegando!*\n\n" .
            "*A sua senha está na posição {$ticket->position}*\n" .
            "Senha: {$ticket->ticket_number}\n" .
            "Fila: {$ticket->queue->name}";

        $response = $this->sendPlainText($number, $text);
        if ($response->failed()) {
            throw new ConnectionException('Falha ao enviar mensagem: ' . $response->body());
        }
        return $response;

    }
}
