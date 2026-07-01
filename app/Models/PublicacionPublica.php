<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicacionPublica extends Model
{
    use HasFactory;

    protected $table = 'publicaciones_publicas';
    protected $primaryKey = 'id_aprobado';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'fecha_publicacion_publica' => 'date',
        'fecha_limite_postulacion' => 'date',
        'salario' => 'decimal:2',
    ];

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_publica', 'id_publica');
    }
}
