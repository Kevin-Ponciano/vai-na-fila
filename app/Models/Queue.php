<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
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
}
