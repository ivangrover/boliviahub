<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = [
        'nombre_via',
        'numero_domicilio',
        'edificio',
        'piso',
        'latitud',
        'longitud',
        'municipio_id'
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }

    // Helper to get full address string if needed
    public function getFullAddressAttribute()
    {
        return "{$this->nombre_via} {$this->numero_domicilio}";
    }
}
