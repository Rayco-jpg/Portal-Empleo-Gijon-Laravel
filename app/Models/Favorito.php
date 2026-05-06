<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_oferta'
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function oferta() {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }
}