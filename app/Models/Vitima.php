<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vitima extends User
{
    protected static function booted()
    {
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->where('role', 'vitima');
        });
    }
}
