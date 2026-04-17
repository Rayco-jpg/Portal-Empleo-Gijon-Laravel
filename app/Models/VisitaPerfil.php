<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaPerfil extends Model
{
    // 1. Nombre de la tabla
    protected $table = 'visitas_perfil'; 

    // 2. Desactivamos timestamps si no los usas (según tu DB)
    public $timestamps = false;

    // 3. Campos rellenables (importante para cuando alguien entre al perfil)
    protected $fillable = [
        'id_candidato',
        'id_visitante', // Si guardas quién mira el perfil (opcional)
        'fecha_visita'
    ];

    // --- RELACIONES ---

    /**
     * Una visita pertenece a un candidato específico.
     */
    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'id_candidato', 'id_candidato');
    }
}