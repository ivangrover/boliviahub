<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescripcionContacto extends Model
{
    use HasFactory;

    protected $table = 'descripcion_contactos';

    protected $fillable = ['tipo', 'valor', 'contacto_id'];

    public function contacto()
    {
        return $this->belongsTo(Contacto::class);
    }
}
