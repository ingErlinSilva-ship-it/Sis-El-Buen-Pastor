<div class="container-fluid pt-4">
    <div class="row mt-2">
        <div class="col-12">

            <div class="card border-0 shadow-lg" style="border-radius: 12px;">

                {{-- Header dinámico: Cambia el color del borde y el icono según la acción --}}
                {{-- Header dinámico: Mantiene el escudo fijo pero cambia el color según la acción --}}
<div class="card-header bg-white border-bottom py-3 px-4"
    style="border-top: 4px solid {{ isset($role->id) ? '#28a745' : '#007bff' }}; border-radius: 12px 12px 0 0;">
    <div class="d-flex align-items-center">
        <div class="rounded-circle p-2 mr-3"
            style="background-color: {{ isset($role->id) ? '#e8f5e9' : '#e7f1ff' }};">
            {{-- Icono fijo fa-user-shield, el color cambia dinámicamente --}}
            <i class="fas fa-user-shield {{ isset($role->id) ? 'text-success' : 'text-primary' }}"></i>
        </div>
        <div>
            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                {{ isset($role->id) ? __('Actualizar Rol') : __('Configuración de Rol') }}
            </h3>
        </div>
    </div>
</div>

                <div class="card-body p-4">
                    <div class="form-group mb-0">
                        <label for="nombre" class="text-dark font-weight-bold mb-2">
                            <i class="fas fa-edit mr-1 text-muted"></i> {{ __('Nombre del Rol') }}
                        </label>

                        <div class="input-group input-group-lg transition-all">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"
                                    style="border-radius: 8px 0 0 8px;">
                                    {{-- El icono del input también cambia de color para hacer juego --}}
                                    <i class="fas fa-tag {{ isset($role->id) ? 'text-success' : 'text-primary' }}"></i>
                                </span>
                            </div>

                            <input type="text" name="nombre"
                                class="form-control border-left-0 @error('nombre') is-invalid @enderror"
                                style="border-radius: 0 8px 8px 0; font-size: 1rem; background-color: #f8fbff;"
                                value="{{ old('nombre', $role?->nombre) }}" id="nombre"
                                placeholder="Ej: Administrador, Doctor, Recepcionista..." required autocomplete="off">

                            @error('nombre')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle mr-1"></i> Use nombres claros para identificar las funciones de
                            cada usuario.
                        </small>
                    </div>
                </div>

                {{-- Footer con botones de acción dinámicos --}}
                <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4"
                    style="border-radius: 0 0 12px 12px;">
                    <a href="{{ route('role.index') }}"
                        class="btn btn-outline-secondary mr-3 px-4 d-flex align-items-center"
                        style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-times-circle mr-2"></i> {{ __('Cancelar') }}
                    </a>

                    {{-- El botón cambia de color e icono según la acción --}}
                    <button type="submit"
                        class="btn {{ isset($role->id) ? 'btn-success' : 'btn-primary' }} px-5 shadow-sm d-flex align-items-center"
                        style="border-radius: 8px; font-weight: 600; letter-spacing: 0.5px;">
                        <i class="fas {{ isset($role->id) ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i>
                        {{ isset($role->id) ? __('Actualizar Rol') : __('Guardar Rol') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        /* Usamos un selector más específico para evitar conflictos con otros formularios */
        .card form .transition-all {
            transition: all 0.3s ease;
        }

        /* Solo afectamos a los controles de este formulario específico */
        .card form .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.2rem
                {{ isset($role->id) ? 'rgba(40, 167, 69, 0.15)' : 'rgba(0, 123, 255, 0.15)' }}
                !important;
            border-color:
                {{ isset($role->id) ? '#28a745' : '#80bdff' }}
                !important;
        }

        /* Efecto de elevación seguro */
        .card form .btn-primary:hover,
        .card form .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px
                {{ isset($role->id) ? 'rgba(40, 167, 69, 0.3)' : 'rgba(0, 123, 255, 0.3)' }}
                !important;
        }
    </style>
@endpush