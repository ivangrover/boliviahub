<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $table = 'contactos';

    protected $fillable = ['tipo_contacto', 'estado', 'persona', 'empresa_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function detalles()
    {
        return $this->hasMany(DescripcionContacto::class);
    }
}
