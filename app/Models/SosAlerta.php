<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SosAlerta extends Model
{
    protected $fillable = ['user_id', 'atendida'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
