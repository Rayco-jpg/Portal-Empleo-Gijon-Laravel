<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    protected $table = 'facturas';

    protected $fillable = [
        'user_id',
        'referencia',
        'importe',
        'concepto',
        'estado'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
