<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $table = 'rubros';
    protected $fillable = ['descripcion', 'empresa_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
