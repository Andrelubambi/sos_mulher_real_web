<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensagemGrupo extends Model
{
    use HasFactory;

    protected $table = 'mensagens_grupo';

    protected $fillable = [
        'grupo_id', 
        'user_id', 
        'conteudo'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
