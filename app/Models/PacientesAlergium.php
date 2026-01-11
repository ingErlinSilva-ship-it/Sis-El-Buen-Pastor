<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PacientesAlergium
 *
 * @property $id
 * @property $paciente_id
 * @property $alergias_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Alergia $alergia
 * @property Paciente $paciente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PacientesAlergium extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['paciente_id', 'alergias_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alergia()
    {
        return $this->belongsTo(\App\Models\Alergia::class, 'alergias_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class, 'paciente_id', 'id');
    }
    
}
