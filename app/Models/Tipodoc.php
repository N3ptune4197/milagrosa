<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipodoc
 *
 * @property $id
 * @property $descripcion
 * @property $abreviatura
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tipodoc extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['descripcion', 'abreviatura'];


}
