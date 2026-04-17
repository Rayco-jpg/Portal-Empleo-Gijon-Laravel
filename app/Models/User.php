<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Nombre de la tabla (coincide con tu migración y código anterior)
    protected $table = 'usuarios';

    // 2. Activamos timestamps porque la nueva migración los incluye ($table->timestamps())
    // Si prefieres no usarlos, tendrías que quitarlos de la migración también.
    public $timestamps = false;

    /**
     * Atributos que se pueden asignar masivamente.
     */
/**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'email',
        'password',
        'tipo_usuario', 
        'fecha',
        'reset_token',  // <-- AÑADE ESTA
        'token_expira', // <-- AÑADE ESTA
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
            'fecha'    => 'datetime', // Para que Laravel lo trate como objeto Carbon
            'token_expira' => 'datetime', // <-- AÑADE ESTO para evitar errores de formato
        ];
    }

    // --- RELACIONES PARA TU TFG ---

    /**
     * Relación con la tabla candidatos.
     */
    public function candidato()
    {
        // 'id_usuario' es la FK en la tabla candidatos
        return $this->hasOne(Candidato::class, 'id_usuario');
    }

    /**
     * Relación con la tabla empresas.
     */
    public function empresa()
    {
        // 'id_usuario' es la FK en la tabla empresas
        return $this->hasOne(Empresa::class, 'id_usuario');
    }
}
