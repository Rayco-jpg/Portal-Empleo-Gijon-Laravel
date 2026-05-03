<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    protected $primaryKey = 'id'; 
    public $timestamps = true; 

    protected $casts = [
        'fecha_inscripcion' => 'datetime', 
        'fecha_apuntado' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'id_usuario',
        'id_oferta',
        'id_candidato',
        'fecha_inscripcion',
        'fecha_apuntado',
        'estado',
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'id_candidato', 'id_candidato');
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id');
    }
}
