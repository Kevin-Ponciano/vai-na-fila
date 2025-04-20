<?php

namespace App\Models;

use App\Enums\QueueTicketStatus;
use App\Enums\QueueType;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Queue extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'supermarket_id',
        'name',
        'type',
        'image_path',
    ];

    protected $appends = [
        'image_url',
    ];

    public function supermarket(): BelongsTo
    {
        return $this->belongsTo(Supermarket::class);
    }

    public function currentTicket()
    {
        return $this->queueTickets()
            ->where('status', QueueTicketStatus::CALLED)
            ->orderByDesc('called_at')   // ← usa o instante de chamada
            ->first();
    }

    public function queueTickets(): HasMany
    {
        return $this->hasMany(QueueTicket::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return $this->image_path
                ? Storage::url($this->image_path)
                : asset(QueueType::from($this->type)->image());
        });
    }
}
