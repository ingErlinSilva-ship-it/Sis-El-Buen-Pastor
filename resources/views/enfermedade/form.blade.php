<div class="card border-0 shadow-lg" style="border-radius: 12px;">

    {{-- Header dinámico --}}
    <div class="card-header bg-white border-bottom py-3 px-4"
        style="border-top: 4px solid {{ isset($enfermedade->id) ? '#28a745' : '#007bff' }}; border-radius: 12px 12px 0 0;">
        <div class="d-flex align-items-center">
            <div class="rounded-circle p-2 mr-3" 
                style="background-color: {{ isset($enfermedade->id) ? '#e8f5e9' : '#e7f1ff' }};">
                {{-- Icono de Virus actualizado --}}
                <i class="fas fa-fw fa-virus {{ isset($enfermedade->id) ? 'text-success' : 'text-primary' }}"></i>
            </div>
            <div>
                <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                    {{ isset($enfermedade->id) ? __('Actualizar Patología') : __('Registro de Enfermedad') }}
                </h3>
            </div>
        </div>
    </div>

    <div class="card-body p-4">

        {{-- Campo: Nombre de la Enfermedad --}}
        <div class="form-group mb-4">
            <label for="nombre" class="text-dark font-weight-bold mb-2">
                <i class="fas fa-edit mr-1 text-muted"></i> {{ __('Nombre de la Enfermedad') }}
            </label>
            <div class="input-group input-group-lg shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-tag {{ isset($enfermedade->id) ? 'text-success' : 'text-primary' }}"></i>
                    </span>
                </div>
                <input type="text" name="nombre" class="form-control border-left-0 @error('nombre') is-invalid @enderror"
                    style="border-radius: 0 8px 8px 0; font-size: 1rem; background-color: #f8fbff;"
                    value="{{ old('nombre', $enfermedade?->nombre) }}" required placeholder="Ej: Influenza">
                @error('nombre')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <small class="form-text text-muted mt-2 font-italic">
                <i class="fas fa-info-circle mr-1"></i> {{ __('Nombre científico o común.') }}
            </small>
        </div>

        {{-- Campo: Descripción --}}
        <div class="form-group mb-0">
            <label for="descripcion" class="text-dark font-weight-bold mb-2">
                <i class="fas fa-align-left mr-1 text-muted"></i> {{ __('Descripción') }}
            </label>
            <div class="input-group shadow-sm">
                <textarea name="descripcion" id="descripcion" rows="4"
                    class="form-control @error('descripcion') is-invalid @enderror"
                    style="border-radius: 8px; background-color: #f8fbff; font-size: 1rem;"
                    placeholder="Escriba aquí los detalles...">{{ old('descripcion', $enfermedade?->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
        </div>
    </div>

    {{-- Botones de Inversión --}}
    <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4"
        style="border-radius: 0 0 12px 12px;">
        
        <a href="{{ route('enfermedade.index') }}"
            class="btn btn-invert mr-3 px-4 d-flex align-items-center shadow-sm">
            <i class="fas fa-times-circle mr-2"></i> {{ __('Cancelar') }}
        </a>

        @if(Auth::user()->rol_id == 1 || (!isset($alergia->id) && Auth::user()->rol_id == 2))
    <button type="submit"
        class="btn {{ isset($alergia->id) ? 'btn-success-invert' : 'btn-primary-invert' }} px-5 shadow-sm d-flex align-items-center">
        <i class="fas {{ isset($alergia->id) ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i>
        {{ isset($alergia->id) ? __('Actualizar Registro') : __('Guardar Registro') }}
    </button>
@endif
    </div>
</div>

@push('css')
<style>
    /* Estilos Base de Inversión */
    .btn-invert, 
    .btn-primary-invert, 
    .btn-success-invert {
        background-color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
        border-width: 2px !important;
    }
    
    .btn-invert {
        border: 2px solid #343a40 !important; 
        color: #343a40 !important;
    }

    .btn-invert:hover {
        background-color: #343a40 !important;
        color: #ffffff !important;
    }

    .btn-primary-invert {
        border: 2px solid #007bff !important;
        color: #007bff !important;
    }

    .btn-primary-invert:hover {
        background-color: #007bff !important;
        color: #ffffff !important;
    }

    .btn-success-invert { 
        border: 2px solid #28a745 !important;
        color: #28a745 !important;
    }

    .btn-success-invert:hover {
        background-color: #28a745 !important;
        color: #ffffff !important;
    }

    .btn:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important; 
    }

    .btn:hover i { 
        color: #ffffff !important;
    }

    .card .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.2rem {{ isset($enfermedade->id) ? 'rgba(40, 167, 69, 0.15)' : 'rgba(0, 123, 255, 0.15)' }} !important;
        border-color: {{ isset($enfermedade->id) ? '#28a745' : '#80bdff' }} !important;
    }
</style>
@endpush