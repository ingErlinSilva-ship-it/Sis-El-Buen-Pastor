<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Paciente
 *
 * @property $id
 * @property $fecha_nacimiento
 * @property $cedula
 * @property $direccion
 * @property $tipo_sangre
 * @property $usuario_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Usuario $usuario
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Paciente extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['fecha_nacimiento', 'cedula', 'direccion', 'tipo_sangre', 'usuario_id','es_menor',
    'tutor_nombre',
    'tutor_apellido',
    'tutor_cedula',
    'tutor_telefono',
    'tutor_parentesco',
    ];

    protected $casts = [    'fecha_nacimiento' => 'date:d-m-Y',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id', 'id');
    }

    // Esto quita la hora 
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
    
    public function alergias()
    {
        return $this->belongsToMany(Alergia::class, 'pacientes_alergia','paciente_id',
        'alergias_id');
    }

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedade::class, 'pacientes_enfermedad','paciente_id',
        'enfermedades_id');
    }
}
