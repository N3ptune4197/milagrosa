<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Prestamo
 *
 * @property $id
 * @property $idPersonal
 * @property $fecha_prestamo
 * @property $fecha_devolucion
 * @property $cantidad_total
 * @property $observacion
 * @property $created_at
 * @property $updated_at
 *
 * @property Personal $personal
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Prestamo extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idPersonal',
        'fecha_prestamo',
        'fecha_devolucion',
        'observacion',
        'fecha_devolucion_real'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'fecha_prestamo',
        'fecha_devolucion',
        'fecha_devolucion_real'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personal()
    {
        return $this->belongsTo(\App\Models\Personal::class, 'idPersonal', 'id');
    }

    /**
     * Get the formatted date for fecha_prestamo.
     */
    public function getFechaPrestamoFormattedAttribute()
    {
        return $this->fecha_prestamo ? $this->fecha_prestamo->format('d/m/Y') : 'N/A';
    }

    /**
     * Get the formatted date for fecha_devolucion.
     */
    public function getFechaDevolucionFormattedAttribute()
    {
        return $this->fecha_devolucion ? $this->fecha_devolucion->format('d/m/Y') : 'N/A';
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($prestamo) {
            $prestamo->fecha_prestamo = now(); // Establecer la fecha de préstamo como la fecha actual
        });
    }
    public function detalleprestamos()
    {
        return $this->hasMany(Detalleprestamo::class, 'idprestamo');
    }

}
