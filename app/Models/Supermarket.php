<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supermarket extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'opening_hours',
        'closing_hours',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function queues(): HasMany|Supermarket
    {
        return $this->hasMany(Queue::class);
    }

    public function queueOffers(): HasMany|Supermarket
    {
        return $this->hasMany(QueueOffer::class);
    }

    public function queueTickets(): HasMany|Supermarket
    {
        return $this->hasMany(QueueTicket::class);
    }

    public function reports(): HasMany|Supermarket
    {
        return $this->hasMany(Report::class);
    }


}
