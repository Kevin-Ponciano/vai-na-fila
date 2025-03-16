<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'supermarket_id',
        'queue_id',
        'product_name',
        'description',
        'price',
        'discount_percentage',
        'start_date',
        'end_date',
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
