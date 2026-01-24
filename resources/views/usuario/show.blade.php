@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- 1. Limpiamos el encabezado para mantener la estética fluida --}}
@section('content_header')
@stop

@section('content')
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                    
                    {{-- Encabezado con color dinámico --}}
                    <div class="card-header bg-white border-bottom py-3 px-4" style="border-top: 4px solid #007bff; border-radius: 15px 15px 0 0;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 mr-3" style="background-color: #e7f1ff;">
                                <i class="fas fa-user-check text-primary"></i>
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
                                        <img src="{{ asset('storage/'.$usuario->foto) }}" 
                                             class="img-thumbnail shadow-sm" 
                                             style="width: 180px; height: 180px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center m-auto shadow-sm" 
                                             style="width: 180px; height: 180px; border-radius: 50%;">
                                            <i class="fas fa-user-circle fa-7x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    @if ($usuario->estado == 1)
                                        <span class="badge badge-success px-3 py-2" style="border-radius: 50px;">
                                            <i class="fas fa-check-circle mr-1"></i> Cuenta Activa
                                        </span>
                                    @else
                                        <span class="badge badge-danger px-3 py-2" style="border-radius: 50px;">
                                            <i class="fas fa-times-circle mr-1"></i> Cuenta Inactiva
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Columna de Datos Personales --}}
                            <div class="col-md-8 px-4">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase d-block">Nombre Completo</label>
                                        <span class="h5 text-dark font-weight-bold">{{ $usuario->nombre }} {{ $usuario->apellido }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase d-block">Rol de Acceso</label>
                                        @php
                                            $roleName = $usuario->role?->nombre ?? 'Sin Rol';
                                            $badgeColor = match(strtolower($roleName)) {
                                                'administrador' => 'bg-primary',
                                                'doctor' => 'bg-info',
                                                'paciente' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }} px-3 py-2" style="border-radius: 8px;">
                                            {{ $roleName }}
                                        </span>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <hr class="mt-0">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase d-block"><i class="fas fa-envelope mr-1"></i> Correo Electrónico</label>
                                        <span class="text-dark">{{ $usuario->email }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase d-block"><i class="fas fa-phone mr-1"></i> Teléfono / Celular</label>
                                        <span class="text-dark">{{ $usuario->celular }}</span>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase d-block"><i class="fas fa-calendar-alt mr-1"></i> Miembro desde</label>
                                        <span class="text-dark">{{ $usuario->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 15px 15px;">
                        {{-- Botón Regresar --}}
                        <a href="{{ route('usuario.index') }}" class="btn btn-outline-secondary mr-3 px-4 d-flex align-items-center shadow-sm" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Regresar') }}
                        </a>
                        
                        {{-- Botón Editar --}}
                        <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-success px-4 d-flex align-items-center shadow-sm" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-user-edit mr-2"></i> {{ __('Editar Información') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <div class="float-right d-none d-sm-block text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop