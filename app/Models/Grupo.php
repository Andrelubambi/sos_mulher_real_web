<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 
        'descricao', 
        'admin_id'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'grupo_user');
    }

    public function mensagens()
    {
        return $this->hasMany(MensagemGrupo::class);
    }

    public function podeSerExcluidoPelo($user)
{
    return $this->admin_id === $user->id || $user->role === 'admin'; 
}

}
