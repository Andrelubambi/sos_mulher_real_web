<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;

    protected $table = 'mensagens';  

    protected $fillable = ['de', 'para', 'conteudo'];

    public function remetente()
    {
        return $this->belongsTo(User::class, 'de');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'para');
    }
}
