<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; 

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'telefone',
        'password',
        'role', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 🔥 MÉTODO ESSENCIAL PARA AUTENTICAÇÃO POR TELEFONE
    public function findForPassport($username)
    {
        return $this->where('telefone', $username)->first();
    }

    public function createTokenForUser(string $tokenName = 'default')
    {
        return $this->createToken($tokenName)->plainTextToken;
    }

    public function isMedico()
    {
        return $this->role === 'doutor';
    }

    public function consultasCriadas()
    {
        return $this->hasMany(Consulta::class, 'criador_id');
    }
    
    public function consultasMedico()
    {
        return $this->hasMany(Consulta::class, 'medico_id');
    }
    

    // 🔥 ADICIONE ESTA RELAÇÃO (consultas onde o user é a vítima)
    public function consultasComoVitima()
    {
        return $this->hasMany(Consulta::class, 'vitima_id');
    } 

  public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_user', 'user_id', 'grupo_id');
    }

    
}





