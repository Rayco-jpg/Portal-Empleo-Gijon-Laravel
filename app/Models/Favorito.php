<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';
    // En tu captura no veo timestamps, así que los desactivamos
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_oferta'
    ];

    // Relaciones para el TFG (opcionales pero recomendadas)
    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function oferta() {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }
}