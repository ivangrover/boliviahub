<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $fillable = ['nombre_interno', 'nombre_publico', 'slug', 'provincia_id'];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }
}
