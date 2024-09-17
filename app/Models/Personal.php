<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Personal
 *
 * @property $id
 * @property $nombres
 * @property $a_paterno
 * @property $a_materno
 * @property $telefono
 * @property $id_tipodocs
 * @property $nro_documento
 * @property $cargo
 * @property $created_at
 * @property $updated_at
 *
 * @property Tipodoc $tipodoc
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Personal extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombres', 'a_paterno', 'a_materno', 'telefono', 'tipodoc', 'nro_documento', 'cargo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipodoc()
    {
        return $this->belongsTo(\App\Models\Tipodoc::class, 'id_tipodocs', 'id');
    }
    
}
