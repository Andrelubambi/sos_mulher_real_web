<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoApoio extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function membros()
    {
        return $this->belongsToMany(User::class, 'grupo_user');
    }
    

}
