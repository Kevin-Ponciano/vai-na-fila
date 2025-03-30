<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'supermarket_id',
        'avg_wait_time',
        'peak_hour',
    ];

    protected $appends = [
        'name',
    ];

    public function supermarket(): BelongsTo
    {
        return $this->belongsTo(Supermarket::class);
    }

    protected function name(): Attribute
    {
        return new Attribute(
            get: fn() => $this->created_at->translatedFormat('F Y'),
        );
    }


}
