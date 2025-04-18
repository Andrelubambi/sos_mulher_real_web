<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['criador_id', 'medico_id', 'descricao', 'bairro', 'provincia', 'data'];

public function criador()
{
    return $this->belongsTo(User::class, 'criador_id');
}

public function medico()
{
    return $this->belongsTo(User::class, 'medico_id');
}

}
