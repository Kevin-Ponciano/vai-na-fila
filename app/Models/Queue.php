<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Queue extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'supermarket_id',
        'name',
        'is_priority',
    ];

    public function supermarket(): BelongsTo
    {
        return $this->belongsTo(Supermarket::class);
    }

    public function queueTickets(): HasMany
    {
        return $this->hasMany(QueueTicket::class);
    }

    public function actualTicket(): HasMany
    {
        return $this->hasMany(QueueTicket::class)->where('status', 'called')->orderBy('created_at', 'desc');
    }
}
