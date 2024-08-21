<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recurso
 *
 * @property $id
 * @property $nombre
 * @property $id_categoria
 * @property $estado
 * @property $fecha_registro
 * @property $modelo
 * @property $nro_serie
 * @property $id_marca
 * @property $created_at
 * @property $updated_at
 *
 * @property Categoria $categoria
 * @property Marca $marca
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Recurso extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'id_categoria',
        'estado',
        'modelo',
        'nro_serie',
        'id_marca',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];
    


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categoria::class, 'id_categoria', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marca()
    {
        return $this->belongsTo(\App\Models\Marca::class, 'id_marca', 'id');
    }

    /**
     * Get the description for the estado attribute.
     */
    public function getEstadoDescripcionAttribute()
    {
        $estados = [
            1 => 'Disponible',
            2 => 'Prestado',
            3 => 'En mantenimiento',
            4 => 'Dañado'
        ];

        return $estados[$this->estado] ?? 'Desconocido';
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
{
    static::creating(function ($recurso) {
        $recurso->estado = 1; // Disponible
        $recurso->fecha_registro = now(); // Fecha de registro predeterminada
    });
}
    
}
