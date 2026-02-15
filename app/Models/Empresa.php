<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'id',
        'nit',
        'matricula',
        'matricula_anterior',
        'razon_social',
        'estado',
        'estado_actualizacion',
        'tipo_unidad_economica',
        'establecimiento_id',
        'mes_cierre_gestion',
        'ultima_actualizacion',
        'slug',
        'direccion_id'
    ];

    public function direccion()
    {
        return $this->belongsTo(Direccion::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_empresa');
    }

    public function rubros()
    {
        return $this->hasMany(Rubro::class);
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class);
    }
}
