<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensagemSos extends Model
{
    use HasFactory;


    protected $table = 'mensagem_sos';
    protected $fillable = ['conteudo','user_id'];
}
