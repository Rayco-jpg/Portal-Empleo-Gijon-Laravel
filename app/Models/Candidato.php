<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    // 1. Nombre de la tabla
    protected $table = 'candidatos';

    // 2. Clave primaria
    protected $primaryKey = 'id_candidato';

    // 3. Desactivar timestamps automáticos
    public $timestamps = false;

    // 4. Columnas rellenables
    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellidos',
        'curriculum',
        'ubicacion',
        'foto'
    ];

    // --- RELACIONES ---

    // Un candidato pertenece a un usuario (Auth)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // Un candidato puede tener muchas inscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_candidato', 'id_candidato');
    }

    // Un candidato recibe muchas visitas a su perfil (para las estadísticas)
    public function visitas()
    {
        return $this->hasMany(VisitaPerfil::class, 'id_candidato', 'id_candidato');
    }
}