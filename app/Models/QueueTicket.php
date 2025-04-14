<?php

namespace App\Models;

use App\Enums\QueueTicketPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QueueTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_id',
        'client_id',
        'ticket_number',
        'priority',
        'status',
        'type',
        'called_at',
        'expired_at',
    ];

    static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $priority = $model->priority ?? QueueTicketPriority::NORMAL;
            $model->ticket_number = self::generateTicketNumber($model->queue_id, $priority);
        });
    }

    static function generateTicketNumber($queueId, $priority = QueueTicketPriority::NORMAL): int
    {
        $lastTicket = self::where('queue_id', $queueId)
            ->where('priority', $priority)
            ->orderBy('id', 'desc')
            ->first();
        $lastTicketNumber = $lastTicket ? $lastTicket->ticket_number : 0;
        return $lastTicketNumber + 1;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    public function notifications(): QueueTicket|HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
