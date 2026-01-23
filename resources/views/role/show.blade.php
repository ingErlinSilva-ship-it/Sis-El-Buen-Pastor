@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content_header')
@stop

@section('content')
    <div class="container-fluid pt-4">
        <div class="row mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                    
                    <div class="card-header bg-white border-bottom py-3 px-4" style="border-top: 4px solid #007bff; border-radius: 12px 12px 0 0;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 mr-3" style="background-color: #e7f1ff;">
                                <i class="fas fa-user-shield text-primary"></i>
                            </div>
                            <div>
                                <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                    {{ __('Visualización del Rol') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="text-dark font-weight-bold mb-2">
                                        <i class="fas fa-edit mr-1 text-muted"></i> {{ __('Nombre del Rol') }}
                                    </label>
                                    
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="border-radius: 8px 0 0 8px;">
                                                <i class="fas fa-tag text-primary"></i>
                                            </span>
                                        </div>
                                        <div class="form-control border-left-0 bg-light" style="border-radius: 0 8px 8px 0; font-size: 1rem; height: auto; min-height: 48px; display: flex; align-items: center;">
                                            {{ $role->nombre }}
                                        </div>
                                    </div>
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle mr-1"></i> Identificador de funciones para el personal de la clínica.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 12px 12px;">
                        {{-- Botón de regreso --}}
                        <a href="{{ route('role.index') }}" class="btn btn-outline-secondary mr-3 px-4 d-flex align-items-center shadow-sm" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Regresar') }}
                        </a>
                        
                        {{-- Botón de edición --}}
                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary px-5 shadow-sm d-flex align-items-center" style="border-radius: 8px; font-weight: 600; letter-spacing: 0.5px;">
                            <i class="fas fa-edit mr-2"></i> {{ __('Editar Rol') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <div class="float-right d-none d-sm-block text-muted small">Version: {{ config('app.version', '1.0.0') }}</div>
    <strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
    <script>
        $(document).ready(function () {
            // Lógica adicional aquí
        });
    </script>
@endpush

@push('css')
    <style type="text/css">
        /* Estilos específicos si fueran necesarios */
    </style>
@endpush