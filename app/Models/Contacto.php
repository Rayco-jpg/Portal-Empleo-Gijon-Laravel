<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contacto extends Model
{
    protected $fillable = [
        'user_id',
        'asunto',
        'mensaje',
        'leido'
    ];

    /**
     * Relación: Un contacto pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'Usuario no encontrado',
            'email' => 'N/A'
        ]);
    }
}
