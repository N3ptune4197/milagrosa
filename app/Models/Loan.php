<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'prestamos'; // Especifica el nombre de la tabla si es diferente a "loans"

    protected $fillable = ['estado', 'idPersonal', 'fecha_devolucion']; // Agrega los campos que sean rellenables
    public function detalles()
    {
        return $this->hasMany(DetallePrestamo::class, 'idprestamo');
    }

    public function user()
    {
        return $this->belongsTo(Personal::class, 'idPersonal');
    }
}
