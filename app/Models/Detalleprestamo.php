<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detalleprestamo
 *
 * @property $id
 * @property $idprestamo
 * @property $id_recurso
 * @property $created_at
 * @property $updated_at
 *
 * @property Prestamo $prestamo
 * @property Recurso $recurso
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detalleprestamo extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['idprestamo', 'id_recurso'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prestamo()
    {
        return $this->belongsTo(\App\Models\Prestamo::class, 'idprestamo', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurso()
    {
        return $this->belongsTo(\App\Models\Recurso::class, 'id_recurso', 'id');
    }
    
}
