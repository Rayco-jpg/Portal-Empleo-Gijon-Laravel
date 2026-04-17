<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    // 1. Nombre de la tabla
    protected $table = 'alertas'; 

    // 2. Clave primaria (según capturas es id_alerta)
    protected $primaryKey = 'id_alerta';

    // 3. Desactivamos timestamps si no existen created_at/updated_at
    public $timestamps = false;

    // 4. Campos rellenables
    protected $fillable = [
        'id_usuario',
        'id_categoria'
    ];

    // --- RELACIONES ---

    // Una alerta pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Una alerta pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}