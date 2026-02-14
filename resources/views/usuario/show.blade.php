@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<div class="container-fluid pt-4 show-mode">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">

                {{-- Encabezado con Identidad Púrpura (Show) --}}
                <div class="card-header bg-white border-bottom py-3 px-4"
                    style="border-top: 5px solid #8e44ad; border-radius: 15px 15px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 mr-3" style="background-color: #f3e5f5;">
                            <i class="fas fa-user-check text-purple-custom"></i>
                        </div>
                        <div>
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.3rem;">
                                {{ __('Perfil de Usuario') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        {{-- Columna de la Foto y Estado --}}
                        <div class="col-md-4 text-center border-right">
                            <div class="mb-3">
                                @if($usuario->foto)
                                    <img src="{{ asset('storage/fotos/' . $usuario->foto) }}" class="img-thumbnail shadow-sm"
                                        style="width: 180px; height: 180px; object-fit: cover; border-radius: 50%; border: 3px solid #8e44ad;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center m-auto shadow-sm"
                                        style="width: 180px; height: 180px; border-radius: 50%; border: 2px dashed #8e44ad;">
                                        <i class="fas fa-user-circle fa-7x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if ($usuario->estado == 1)
                                    <span class="badge px-3 py-2" style="border-radius: 50px; background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9;">
                                        <i class="fas fa-check-circle mr-1"></i> Cuenta Activa
                                    </span>
                                @else
                                    <span class="badge px-3 py-2" style="border-radius: 50px; background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2;">
                                        <i class="fas fa-times-circle mr-1"></i> Cuenta Inactiva
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Columna de Datos Personales --}}
                        <div class="col-md-8 px-4 text-left">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="text-muted small font-weight-bold text-uppercase d-block">Nombre Completo</label>
                                    <span class="h5 text-dark font-weight-bold">{{ $usuario->nombre }} {{ $usuario->apellido }}</span>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="text-muted small font-weight-bold text-uppercase d-block">Rol de Acceso</label>
                                    @php
                                    $roleName = $usuario->role?->nombre ?? 'Sin Rol';
                                    $badgeClass = match (strtolower($roleName)) {
                                        'administrador' => 'badge-primary',
                                        'doctor' => 'badge-info',
                                        'paciente' => 'badge-warning',
                                        default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 shadow-sm" style="border-radius: 8px;">
                                        {{ $roleName }}
                                    </span>
                                </div>
                                <div class="col-12 mb-3 text-left">
                                    <hr class="mt-0">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="text-muted small font-weight-bold text-uppercase d-block">
                                        <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                                    </label>
                                    <span class="text-dark">{{ $usuario->email }}</span>
                                </div>
                                <div class="col-sm-6 mb-3 text-left">
                                    <label class="text-muted small font-weight-bold text-uppercase d-block">
                                        <i class="fas fa-phone mr-1"></i> Teléfono / Celular
                                    </label>
                                    <span class="text-dark">{{ $usuario->celular ?? 'No registrado' }}</span>
                                </div>
                                <div class="col-sm-6 mb-3 text-left">
                                    <label class="text-muted small font-weight-bold text-uppercase d-block">
                                        <i class="fas fa-calendar-alt mr-1"></i> Miembro desde
                                    </label>
                                    <span class="text-dark">
                                        {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'Sin fecha' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4"
                    style="border-radius: 0 0 15px 15px;">
                    
                    {{-- Botón Regresar --}}
                    <a href="{{ route('usuario.index') }}" class="btn btn-invert mr-3 px-4">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('Regresar') }}
                    </a>

                    {{-- Botón Editar --}}
                    <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-purple-invert px-4">
                        <i class="fas fa-user-edit mr-2"></i>
                        @if(Auth::user()->rol_id == 1)
                        {{ __('Editar Información') }}
                        @else
                        {{ __('Editar Mi Información') }}
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
    <style>
        .text-purple-custom {
            color: #8e44ad !important;
        }

        /* BOTÓN NEGRO (Regresar) */
        .btn-invert {
            background-color: #ffffff !important;
            border: 2px solid #343a40 !important;
            color: #343a40 !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease;
        }

        .btn-invert:hover {
            background-color: #343a40 !important;
            color: #ffffff !important;
        }

        /* BOTÓN PÚRPURA (Editar) */
        a.btn-purple-invert {
            border: 2px solid #8e44ad !important;
            color: #8e44ad !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease-in-out !important;
            display: inline-flex;
            align-items: center;
            text-decoration: none !important;
        }

        a.btn-purple-invert i {
            color: #8e44ad !important;
        }

        /* INVERSIÓN AL PASAR EL MOUSE */
        a.btn-purple-invert:hover {
            background-color: #8e44ad !important;
            color: #ffffff !important;
            border-color: #8e44ad !important;
        }

        a.btn-purple-invert:hover i {
            color: #ffffff !important;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(142, 68, 173, 0.2) !important;
        }
    </style>
@endpush