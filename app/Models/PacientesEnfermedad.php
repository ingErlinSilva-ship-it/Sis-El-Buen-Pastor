<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PacientesEnfermedad
 *
 * @property $id
 * @property $paciente_id
 * @property $enfermedades_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Enfermedade $enfermedade
 * @property Paciente $paciente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PacientesEnfermedad extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['paciente_id', 'enfermedades_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enfermedade()
    {
        return $this->belongsTo(\App\Models\Enfermedade::class, 'enfermedades_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class, 'paciente_id', 'id');
    }
    
}
