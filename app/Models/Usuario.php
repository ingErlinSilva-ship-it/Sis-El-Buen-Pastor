<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne; // Importar la clase para la relación uno a uno

/**
 * Class Usuario
 *
 * @property $id
 * @property $nombre
 * @property $apellido
 * @property $celular
 * @property $foto
 * @property $email
 * @property $email_verified_at
 * @property $password
 * @property $estado
 * @property $rol_id
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 *
 * @property Role $role
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Usuario extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'apellido', 'celular', 'foto', 'email', 'password','estado', 'rol_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'rol_id');
    }

    // NUEVA RELACIÓN: Un Usuario puede ser un Médico
    /**
     * Define la relación uno-a-uno con el modelo Medico.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function medico(): HasOne
    {
        // La clave foránea en la tabla 'medicos' es 'usuario_id'
        return $this->hasOne(Medico::class, 'usuario_id', 'id');
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'usuario_id', 'id');
    }
    
}
