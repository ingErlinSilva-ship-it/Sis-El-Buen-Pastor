@extends('adminlte::page')

@section('title', config('adminlte.title'))

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <form method="POST" action="{{ route('medico.store') }}" role="form" enctype="multipart/form-data">
                @csrf
                
                <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                    {{-- Header Azul de Creación --}}
                    <div class="card-header bg-white py-3 shadow-sm" style="border-top: 5px solid #007bff;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 mr-3" style="background-color: #e7f1ff; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-plus text-primary"></i>
                            </div>
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                {{ __('Registrar Nuevo Expediente Médico') }}
                            </h3>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Panel Lateral: Identidad Visual --}}
                            <div class="col-md-4 text-center border-right">
                                <div class="mb-4 d-flex justify-content-center mt-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                        style="width: 140px; height: 140px; border: 2px dashed #007bff;">
                                        <i class="fas fa-user-md text-primary" style="font-size: 4.5rem;"></i>
                                    </div>
                                </div>
                                
                                <h4 class="font-weight-bold text-dark mb-1">{{ __('Nuevo Especialista') }}</h4>
                                <p class="text-muted small mb-3">{{ __('Asignación de credenciales') }}</p>

                                <span class="badge-role-create shadow-sm">
                                    <i class="fas fa-shield-alt mr-1"></i> {{ __('Modo Registro') }}
                                </span>
                                
                                <p class="text-muted small px-3 mt-4">
                                    {{ __('Seleccione un usuario de la lista para vincularlo como médico y asigne su especialidad correspondiente.') }}
                                </p>
                            </div>

                            {{-- Columna Derecha: Campos con Barrita Lateral AZUL --}}
                            <div class="col-md-8">
                                <div class="row px-3">
                                    
                                    {{-- Usuario --}}
                                    <div class="col-md-12 mb-4">
                                        <label class="label-custom text-primary"><i class="fas fa-user-circle mr-1"></i> Seleccionar Usuario del Sistema</label>
                                        <div class="input-box-container-blue">
                                            <select name="usuario_id" class="form-control-custom @error('usuario_id') is-invalid @enderror">
                                                <option value="">{{ __('--- Seleccione un usuario existente ---') }}</option>
                                                @foreach ($usuarios as $id => $nombre)
                                                    <option value="{{ $id }}" {{ old('usuario_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('usuario_id') <small class="text-danger ml-2">{{ $message }}</small> @enderror
                                    </div>

                                    {{-- Especialidad --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="label-custom text-primary"><i class="fas fa-fw fa-microscope mr-1"></i> Especialidad Médica</label>
                                        <div class="input-box-container-blue">
                                            <select name="especialidad_id" class="form-control-custom @error('especialidad_id') is-invalid @enderror">
                                                <option value="">{{ __('--- Seleccione ---') }}</option>
                                                @foreach ($especialidades as $id => $nombre)
                                                    <option value="{{ $id }}" {{ old('especialidad_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('especialidad_id') <small class="text-danger ml-2">{{ $message }}</small> @enderror
                                    </div>

                                    {{-- Código MINSA --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="label-custom text-primary"><i class="fas fa-id-card mr-1"></i> Código MINSA</label>
                                        <div class="input-box-container-blue">
                                            <input type="text" name="codigo_minsa" class="form-control-custom font-weight-bold" 
                                                   placeholder="Ej: 123456" value="{{ old('codigo_minsa') }}" maxlength="6">
                                        </div>
                                        @error('codigo_minsa') <small class="text-danger ml-2">{{ $message }}</small> @enderror
                                    </div>

                                    {{-- Descripción --}}
                                    <div class="col-md-12">
                                        <label class="label-custom text-primary"><i class="fas fa-file-alt mr-1"></i> Descripción / Observaciones</label>
                                        <div class="input-box-container-blue">
                                            <textarea name="descripcion" class="form-control-custom" rows="4" placeholder="Breve reseña del perfil profesional...">{{ old('descripcion') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 d-flex justify-content-end py-3 px-4">
                        <a href="{{ route('medico.index') }}" class="btn btn-invert mr-3 px-4 shadow-sm">
                            <i class="fas fa-times-circle mr-2"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary-invert px-5 shadow-sm">
                            <i class="fas fa-save mr-2"></i> {{ __('Guardar Médico') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    /* Barrita lateral para creación */
    .input-box-container-blue {
        background-color: #f8fbff;
        border-left: 5px solid #007bff !important;
        border-radius: 8px;
    }

    .form-control-custom {
        background: transparent !important;
        border: 1px solid #e2e8f0 !important;
        border-left: none !important;
        border-radius: 0 8px 8px 0;
        padding: 12px 15px;
        height: auto;
        width: 100%;
        color: #334155;
        font-weight: 500;
    }

    .form-control-custom:focus {
        outline: none;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
    }

    .label-custom {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 5px;
        display: block;
    }

    .badge-role-create {
        background-color: #e7f1ff;
        color: #004085;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid #007bff;
        display: inline-block;
    }

    /* Botones de Inversión */
    .btn-invert, .btn-primary-invert {
        background-color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease;
        border-width: 2px !important;
    }
    .btn-invert { 
        border: 2px solid #343a40 !important; 
        color: #343a40 !important; 
    }

    .btn-invert:hover { 
        background-color: #343a40 !important; 
        color: #fff !important; 
    }
    
    .btn-primary-invert { 
        border: 2px solid #007bff !important; 
        color: #007bff !important; 
    }


    .btn-primary-invert:hover { 
        background-color: #007bff !important; 
        color: #fff !important; 
    }

    .btn:hover { 
        transform: translateY(-2px); 
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Solo números en Código MINSA
        $('input[name="codigo_minsa"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                customClass: { popup: 'rounded-lg' }
            });
        @endif
    });
</script>
@endpush