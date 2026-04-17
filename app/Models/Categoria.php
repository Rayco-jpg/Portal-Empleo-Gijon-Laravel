<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // 1. Nombre de la tabla en tu phpMyAdmin
    protected $table = 'categorias'; 

    // 2. Clave primaria específica (visto en tus capturas)
    protected $primaryKey = 'id_categoria'; 

    // 3. Desactivar timestamps ya que la tabla no los tiene
    public $timestamps = false;

    // 4. Campos que se pueden rellenar
    protected $fillable = [
        'nombre_categoria'
    ];

    // --- RELACIONES ---
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Una categoría puede estar en muchas alertas de usuarios.
     */
    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'id_categoria', 'id_categoria');
    }
}