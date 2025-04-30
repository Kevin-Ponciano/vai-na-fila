<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['supermarket_id', 'avg_wait_time', 'peak_hour'];

    public function supermarket()
    {
        return $this->belongsTo(Supermarket::class);
    }
}