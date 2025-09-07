<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'criada_por', 
        'medico_id', 
        'descricao', 
        'bairro', 
        'provincia', 
        'data',
        'status',
        'vitima_id',
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criada_por');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }
}
