@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
@stop

@section('content')
    <div class="container-fluid pt-4">
        <div class="row mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-lg" style="border-radius: 12px;">

                    <div class="card-header bg-white border-bottom py-3 px-4" style="border-top: 4px solid #8e44ad; border-radius: 12px 12px 0 0;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 mr-3" style="background-color: #f3e5f5;">
                                <i class="fas fa-key text-purple-custom"></i>
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
                                                {{-- Icono Morado --}}
                                                <i class="fas fa-tag text-purple-custom"></i>
                                            </span>
                                        </div>
                                        {{-- Fondo lila suave para el campo de lectura --}}
                                        <div class="form-control border-left-0 bg-purple-light" style="border-radius: 0 8px 8px 0; font-size: 1rem; height: auto; min-height: 48px; display: flex; align-items: center; pointer-events: none;">
                                            {{ $role->nombre }}
                                        </div>
                                    </div>
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle mr-1 text-muted"></i> Identificador de funciones para el personal de la clínica.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones Invertidos --}}
                    <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 12px 12px;">
                        
                        <a href="{{ route('role.index') }}" class="btn btn-invert mr-3 px-4 d-flex align-items-center shadow-sm">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Regresar') }}
                        </a>
                        
                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-purple-invert px-5 shadow-sm d-flex align-items-center">
                            <i class="fas fa-edit mr-2"></i> {{ __('Editar Rol') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style type="text/css">
        .text-purple-custom { color: #8e44ad !important; }
        .bg-purple-light { background-color: #fcfaff !important; border: 1px solid #e2e8f0 !important; }

        /* Estilos Base de Inversión */
        .btn-invert, .btn-purple-invert {
            background-color: #ffffff !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease-in-out !important;
            border-width: 2px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* BOTÓN REGRESAR */
        a.btn-invert { 
            border: 2px solid #343a40 !important; 
            color: #343a40 !important; 
        }
        a.btn-invert:hover { 
            background-color: #343a40 !important; 
            color: #ffffff !important; 
        }

        /* BOTÓN EDITAR */
        a.btn-purple-invert { 
            border: 2px solid #8e44ad !important; 
            color: #8e44ad !important;
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