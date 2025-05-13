<?php

namespace App\Models;

use App\Enums\QueueTicketPriority;
use App\Enums\QueueTicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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

    protected $casts = [
        'called_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $appends = [
        'status_name',
    ];

    static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $priority = $model->priority ?? QueueTicketPriority::NORMAL;
            $model->ticket_number = self::generateTicketNumber($model->queue_id, $priority);
        });
    }

    static function generateTicketNumber($queueId, $priority = QueueTicketPriority::NORMAL): string
    {
        $queuePrefix = Str::upper(Str::substr($priority, 0, 2));
        $lastTicket = self::where('queue_id', $queueId)
            ->where('priority', $priority)
            ->orderBy('id', 'desc')
            ->first();
        $lastTicketNumber = $lastTicket
            ? Str::after($lastTicket->ticket_number, $queuePrefix)
            : 0;

        $nextTicketNumber = (int)$lastTicketNumber + 1;
        return $queuePrefix . str_pad($nextTicketNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Adiciona um “OR (…)” que seleciona tickets EXPIRADOS
     * cujo called_at ocorreu dentro da janela dada.
     *
     * Ex.:  ->whereIn('status', …)->orExpiredStillValid(5)
     *
     * @param Builder $query
     * @param int $minutes Janela em minutos (default = 5)
     * @return Builder
     */
    public function scopeOrExpiredStillValid(
        Builder $query,
        int     $minutes = 5
    ): Builder
    {
        $windowStart = Carbon::now()->subMinutes($minutes);

        return $query->orWhere(function (Builder $sub) use ($windowStart) {
            $sub->where('status', QueueTicketStatus::EXPIRED)
                ->where('called_at', '>=', $windowStart);
        });
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

    protected function statusName(): Attribute
    {
        $statusEnum = QueueTicketStatus::from($this->status);
        return new Attribute(
            get: fn() => $statusEnum->name(),
        );
    }
}
