@extends('adminlte::page')

@section('title', config('adminlte.title'))

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white py-3 shadow-sm" style="border-top: 5px solid #8e44ad;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 mr-3" style="background-color: #f3e5f5; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-id-card-alt text-purple-custom"></i>
                        </div>
                        <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                            {{ Auth::user()->rol_id == 2 ? __('Mi Expediente Médico') : __('Expediente del Médico') }}
                        </h3>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center border-right">
                            <div class="p-2">
                                <div class="mb-4 d-flex justify-content-center">
                                    @if($medico->usuario && $medico->usuario->foto)
                                        <div class="rounded-circle shadow-sm" style="width: 150px; height: 150px; overflow: hidden; border: 5px solid #8e44ad;">
                                            <img src="{{ asset('storage/' . $medico->usuario->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                            style="width: 150px; height: 150px; border: 2px dashed #8e44ad;">
                                            <i class="fas fa-user-md text-purple-custom" style="font-size: 4.5rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <h4 class="font-weight-bold text-dark mb-1 text-capitalize">{{ explode(' ', $medico->usuario->nombre)[0] }} {{ explode(' ', $medico->usuario->apellido)[0] }}</h4>
                                <p class="text-muted mb-3">{{ $medico->usuario->email }}</p>
                                <span class="badge-pill-purple shadow-sm">
                                    <i class="fas fa-user-tag mr-1"></i> {{ optional($medico->usuario->role)->nombre ?? 'Médico' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row px-3">
                                <div class="col-md-6 mb-4">
                                    <label class="label-custom-purple"><i class="fas fa-fingerprint mr-1"></i> Código MINSA</label>
                                    <div class="show-box-purple font-weight-bold">{{ $medico->codigo_minsa }}</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="label-custom-purple"><i class="fas fa-fw fa-microscope mr-1"></i> Especialidad</label>
                                    <div class="show-box-purple">{{ $medico->especialidade->nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="label-custom-purple"><i class="fas fa-phone mr-1"></i> Teléfono</label>
                                    <div class="show-box-purple">{{ $medico->usuario->celular ?? 'No registrado' }}</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="label-custom-purple"><i class="fas fa-file-alt mr-1"></i> Descripción Profesional</label>
                                    <div class="show-box-purple text-justify font-italic" style="min-height: 80px;">{{ $medico->descripcion ?: 'Sin descripción disponible.' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-0 d-flex justify-content-end py-3 px-4">
                    <a href="{{ route('medico.index') }}" class="btn btn-invert mr-3 px-4 shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('Regresar') }}
                    </a>
                    <a href="{{ route('medico.edit', $medico->id) }}" class="btn btn-purple-invert px-5 shadow-sm">
                        <i class="fas fa-user-edit mr-2"></i>
                        {{ Auth::user()->rol_id == 2 ? __('Editar Mi Perfil') : __('Editar Perfil') }}
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

    .show-box-purple {
        background-color: #fcfaff !important;
        border: 1px solid #e2e8f0 !important;
        border-left: 5px solid #8e44ad !important;
        border-radius: 8px;
        padding: 12px 15px;
        color: #334155;
        font-weight: 500;
        width: 100%;
        display: block;
    }

    .label-custom-purple { 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        font-weight: 800; 
        color: #8e44ad; 
        margin-bottom: 5px; 
        display: block; 
    }
    
    .badge-pill-purple { 
        background-color: #f3e5f5; 
        color: #8e44ad; 
        padding: 6px 16px; 
        border-radius: 50px; 
        font-size: 0.85rem; 
        font-weight: 700; 
        border: 1px solid #8e44ad; 
    }
    
    /* BOTONES INVERTIDOS */
    .btn-invert, a.btn-purple-invert { 
        background-color: #fff !important; 
        border-radius: 8px !important; 
        font-weight: 600 !important; 
        border-width: 2px !important; 
        transition: all 0.3s ease; 
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Botón Regresar */
    .btn-invert { 
        border: 2px solid #343a40 !important; 
        color: #343a40 !important; 
    }
    
    .btn-invert:hover { 
        background-color: #343a40 !important; 
        color: #fff !important; 
    }
    
    /* BOTÓN EDITAR */
    a.btn-purple-invert { 
        border: 2px solid #8e44ad !important; 
        color: #8e44ad !important; 
    }
    
    a.btn-purple-invert i {
        color: #8e44ad !important;
        transition: all 0.3s ease;
    }
    
    a.btn-purple-invert:hover { 
        background-color: #8e44ad !important; 
        color: #fff !important; 
    }

    a.btn-purple-invert:hover i {
        color: #fff !important;
    }

    /* Efecto de elevación al pasar el mouse */
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(142, 68, 173, 0.2) !important;
    }
</style>
@endpush