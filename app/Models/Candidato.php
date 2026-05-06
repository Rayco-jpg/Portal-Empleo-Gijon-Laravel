<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    protected $table = 'candidatos';

    protected $primaryKey = 'id_candidato';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellidos',
        'curriculum',
        'ubicacion',
        'foto'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_candidato', 'id_candidato');
    }

    public function visitas()
    {
        return $this->hasMany(VisitaPerfil::class, 'id_candidato', 'id_candidato');
    }
}