<div class="card border-0 shadow-lg" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3 px-4"
        style="border-top: 4px solid {{ isset($alergia->id) ? '#28a745' : '#007bff' }}; border-radius: 12px 12px 0 0;">
        <div class="d-flex align-items-center">
            <div class="rounded-circle p-2 mr-3"
                style="background-color: {{ isset($alergia->id) ? '#e8f5e9' : '#e7f1ff' }};">
                <i class="fas fa-fw fa-allergies {{ isset($alergia->id) ? 'text-success' : 'text-primary' }}"></i>
            </div>
            <div>
                <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                    {{ isset($alergia->id) ? __('Actualizar Alergia') : __('Registro de Alergia') }}
                </h3>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="form-group mb-4">
            <label for="nombre" class="text-dark font-weight-bold mb-2">
                <i class="fas fa-edit mr-1 text-muted"></i> {{ __('Nombre de la Alergia') }}
            </label>
            <div class="input-group input-group-lg shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-tag {{ isset($alergia->id) ? 'text-success' : 'text-primary' }}"></i>
                    </span>
                </div>
                <input type="text" name="nombre"
                    class="form-control border-left-0 @error('nombre') is-invalid @enderror"
                    style="border-radius: 0 8px 8px 0; font-size: 1rem; background-color: #f8fbff;"
                    value="{{ old('nombre', $alergia?->nombre) }}" required placeholder="Ej: Penicilina">
                @error('nombre')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
        </div>

        <div class="form-group mb-0">
            <label for="descripcion" class="text-dark font-weight-bold mb-2">
                <i class="fas fa-align-left mr-1 text-muted"></i> {{ __('Descripción / Notas') }}
            </label>
            <div class="input-group shadow-sm">
                <textarea name="descripcion" id="descripcion" rows="4"
                    class="form-control @error('descripcion') is-invalid @enderror"
                    style="border-radius: 8px; background-color: #f8fbff; font-size: 1rem;"
                    placeholder="Detalles sobre reacciones o cuidados...">{{ old('descripcion', $alergia?->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4"
        style="border-radius: 0 0 12px 12px;">
        <a href="{{ route('alergia.index') }}" class="btn btn-invert mr-3 px-4 d-flex align-items-center shadow-sm">
            <i class="fas fa-times-circle mr-2"></i> {{ __('Cancelar') }}
        </a>

        <button type="submit"
            class="btn {{ isset($alergia->id) ? 'btn-success-invert' : 'btn-primary-invert' }} px-5 shadow-sm d-flex align-items-center">
            <i class="fas {{ isset($alergia->id) ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i>
            {{ isset($alergia->id) ? __('Actualizar Registro') : __('Guardar Registro') }}
        </button>
    </div>
</div>

@push('css')
    <style>
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
        }

        .btn:hover i {
            color: #ffffff !important;
        }

        .card .form-control:focus {
            background-color: #fff !important;
            border-color:
                {{ isset($alergia->id) ? '#28a745' : '#80bdff' }}
                !important;
            box-shadow: 0 0 0 0.2rem
                {{ isset($alergia->id) ? 'rgba(40, 167, 69, 0.1)' : 'rgba(0, 123, 255, 0.1)' }}
                !important;
        }
    </style>
@endpush