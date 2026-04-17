<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    
    // En Laravel, si tu columna es 'id', no hace falta ponerlo, 
    // pero dejarlo aquí no molesta y asegura que no haya fallos en el TFG.
    protected $primaryKey = 'id'; 

    public $timestamps = false;

    protected $casts = [
        // Esto es vital para que el ->format('d/m/Y') en la vista no pete
        'fecha_inscripcion' => 'datetime', 
    ];

    protected $fillable = [
        'id_oferta',
        'id_candidato',
        'fecha_inscripcion',
        'estado'
    ];

    /**
     * Relación con el Candidato
     */
    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'id_candidato', 'id_candidato');
    }

    /**
     * Relación con la Oferta
     */
    public function oferta()
    {
        // IMPORTANTE: Asegúrate de que en la tabla 'ofertas' la clave primaria sea 'id'
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id');
    }
}
