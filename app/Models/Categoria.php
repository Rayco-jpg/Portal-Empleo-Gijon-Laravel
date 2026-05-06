<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias'; 
    protected $primaryKey = 'id_categoria'; 

    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria'
    ];

    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_categoria', 'id_categoria');
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'id_categoria', 'id_categoria');
    }
}