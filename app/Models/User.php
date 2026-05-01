<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'password',
        'tipo_usuario', 
        'fecha',
        'reset_token',  
        'token_expira',
        'es_premium',   
        'premium_hasta', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting de atributos.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'fecha'    => 'datetime', 
            'token_expira' => 'datetime',
            'es_premium' => 'boolean',     
            'premium_hasta' => 'datetime', 
        ];
    }

    public function esUsuarioPremium()
    {
        return $this->es_premium && ($this->premium_hasta === null || $this->premium_hasta->isFuture());
    }

    public function candidato()
    {
        return $this->hasOne(Candidato::class, 'id_usuario');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id_usuario');
    }
}
