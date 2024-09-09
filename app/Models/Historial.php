<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Historial
 *
 * @property $id
 * @property $id_detalle_prestamo
 * @property $created_at
 * @property $updated_at
 *
 * @property Detalleprestamo $detalleprestamo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Historial extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_detalle_prestamo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detalleprestamo()
    {
        return $this->belongsTo(\App\Models\Detalleprestamo::class, 'id_detalle_prestamo', 'id');
    }
    
}
