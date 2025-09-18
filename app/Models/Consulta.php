<?php

namespace App\Models;

use App\Enums\ConsultaStatus;
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => ConsultaStatus::class,
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criada_por');
    } 

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function vitima()
    {
        return $this->belongsTo(User::class, 'vitima_id');
    }
}