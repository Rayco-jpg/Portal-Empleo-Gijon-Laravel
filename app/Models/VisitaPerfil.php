<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaPerfil extends Model
{
    protected $table = 'visitas_perfil'; 
    public $timestamps = false;
    protected $fillable = [
        'id_candidato',
        'id_visitante', 
        'fecha_visita'
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'id_candidato', 'id_candidato');
    }
}