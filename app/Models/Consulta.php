<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Consulta
 *
 * @property $id
 * @property $paciente_id
 * @property $medico_id
 * @property $cita_id
 * @property $sintomas
 * @property $diagnostico
 * @property $prescripcion
 * @property $peso
 * @property $estatura
 * @property $presion_arterial
 * @property $temperatura
 * @property $frecuencia_cardiaca
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property Cita $cita
 * @property Medico $medico
 * @property Paciente $paciente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Consulta extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['paciente_id', 'medico_id', 'cita_id', 'sintomas', 'diagnostico', 'prescripcion', 'peso', 'estatura', 'presion_arterial', 'temperatura', 'frecuencia_cardiaca', 'observaciones'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cita()
    {
        return $this->belongsTo(\App\Models\Cita::class, 'cita_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medico()
    {
        return $this->belongsTo(\App\Models\Medico::class, 'medico_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class, 'paciente_id', 'id');
    }
    
}
