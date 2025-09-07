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
        'vitima_id', // ✅ ADICIONAR ESTE CAMPO
        'descricao', 
        'bairro', 
        'provincia', 
        'data',
        'horario', // ✅ Verifique se existe
        'status'    // ✅ Verifique se existe
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criada_por');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    // ✅ ADICIONAR RELAÇÃO COM VÍTIMA
    public function vitima()
    {
        return $this->belongsTo(User::class, 'vitima_id');
    }
}