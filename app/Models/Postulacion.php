<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulaciones';
    protected $primaryKey = 'id_postulacion';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function publicacion()
    {
        return $this->belongsTo(PublicacionPublica::class, 'id_publica', 'id_publica');
    }
}
