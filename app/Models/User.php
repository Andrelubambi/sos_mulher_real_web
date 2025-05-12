<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;


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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
    
    public function grupos()
    {
        return $this->belongsToMany(GrupoApoio::class, 'grupo_user');
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if ($user->password) {
                $user->password = Hash::make($user->password);
            }
        });

        static::updating(function ($user) {
            if ($user->password) {
                $user->password = Hash::make($user->password);
            }
        });
    }
}
