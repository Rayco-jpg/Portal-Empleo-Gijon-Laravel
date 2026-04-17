<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre_empresa',
        'sector',
        'ubicacion',
        'foto'
    ];

    // Una empresa tiene muchas ofertas
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_empresa', 'id_empresa');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}