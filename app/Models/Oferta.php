<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oferta extends Model
{
    use HasFactory;

    protected $table = 'ofertas';
    public $timestamps = false; 
    protected $primaryKey = 'id';

    protected $casts = [
        'fecha_oferta' => 'date',
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    protected $fillable = [
        'id_usuario',   
        'id_empresa',
        'id_categoria',
        'titulo',
        'descripcion',
        'zona_gijon',
        'salario',
        'jornada',
        'experiencia',
        'latitud',
        'longitud',
        'fecha_oferta',
        'estado'
    ];

    // --- RELACIONES ---

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function datosEmpresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    public function categoriaRelacion()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_oferta', 'id');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'id_oferta', 'id');
    }
}
