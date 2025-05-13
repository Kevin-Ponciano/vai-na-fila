<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExpiredStillValidScope implements Scope
{
    const EXPIRATION_TIME_MAX = 5;   // minutos
    public function apply(Builder $builder, Model $model): void
    {
        $cutoff = now()->subMinutes(self::EXPIRATION_TIME_MAX);

        $builder->where(function ($query) use ($cutoff) {
            $query->whereIn('status', [
                'waiting',
                'calling',
                'in_service',
            ])
            ->orWhere(function ($query) use ($cutoff) {
                $query->where('status', 'expired')
                    ->where('called_at', '>=', $cutoff);
            });
        });
    }
}
