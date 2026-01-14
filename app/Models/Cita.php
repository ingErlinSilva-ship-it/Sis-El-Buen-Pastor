<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cita
 *
 * @property $id
 * @property $paciente_id
 * @property $medico_id
 * @property $fecha
 * @property $hora
 * @property $duracion_minutos
 * @property $motivo
 * @property $estado
 * @property $origen
 * @property $chat_session_id
 * @property $token_confirmacion
 * @property $created_at
 * @property $updated_at
 *
 * @property Medico $medico
 * @property Paciente $paciente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cita extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['paciente_id', 'medico_id', 'fecha', 'hora', 'duracion_minutos', 'motivo', 'estado', 'origen', 'chat_session_id', 'token_confirmacion'];


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
