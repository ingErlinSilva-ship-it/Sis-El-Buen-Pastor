<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Medico
 *
 * @property $id
 * @property $usuario_id
 * @property $especialidad_id
 * @property $codigo_minsa
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @property Especialidade $especialidade
 * @property Usuario $usuario
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Medico extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['usuario_id', 'especialidad_id', 'codigo_minsa', 'descripcion'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function especialidade()
    {
        return $this->belongsTo(\App\Models\Especialidade::class, 'especialidad_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id', 'id');
    }
    
}
