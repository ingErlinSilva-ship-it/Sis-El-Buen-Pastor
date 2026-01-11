<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Alergia
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Alergia extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre','descripcion'];

    
    public function pacientes()
    {
        return $this->belongsToMany(Paciente::class, 'pacientes_alergia');
    }


}
