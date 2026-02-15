<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = ['id', 'nombre_interno', 'nombre_publico', 'slug'];

    public function provincias()
    {
        return $this->hasMany(Provincia::class);
    }
}
