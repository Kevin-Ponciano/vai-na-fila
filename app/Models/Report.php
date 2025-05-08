<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'supermarket_id',
        'queue_id',
        'avg_wait_time',
        'peak_hour',
        'max_priority_tickets',
        'min_priority_tickets',
        'avg_priority_tickets',
        'max_general_tickets',
        'min_general_tickets',
        'avg_general_tickets',
        'total_tickets',
    ];

    public function supermarket(): BelongsTo
    {
        return $this->belongsTo(Supermarket::class);
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }
}