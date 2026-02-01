<div class="card border-0 shadow-lg"
    style="border-radius: 15px; border-top: 5px solid {{ isset($medico->id) ? '#28a745' : '#007bff' }};">
    <div class="card-body p-4">
        <div class="row">
            {{-- Panel Lateral Dinámico con detección de foto --}}
            <div class="col-md-4 text-center border-right d-flex flex-column justify-content-center">
                <div class="mb-3 d-flex justify-content-center">
                    @if(isset($medico->usuario) && $medico->usuario->foto)
                        {{-- Foto del médico con borde dinámico --}}
                        <div class="rounded-circle shadow-sm"
                            style="width: 130px; height: 130px; overflow: hidden; border: 5px solid {{ isset($medico->id) ? '#28a745' : '#007bff' }};">
                            <img src="{{ asset('storage/' . $medico->usuario->foto) }}" alt="Foto del Médico"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @else
                        {{-- Icono médico por defecto --}}
                        <i class="fas fa-user-md {{ isset($medico->id) ? 'text-success' : 'text-primary' }}"
                            style="font-size: 5.5rem;"></i>
                    @endif
                </div>

                <h4 class="font-weight-bold text-dark mb-1">
                    {{ isset($medico->id) ? __('Actualizar Médico') : __('Registro de Médico') }}
                </h4>

                @if(isset($medico->id))
                    <span class="badge {{ isset($medico->id) ? 'badge-success' : 'badge-primary' }} mb-2 px-3 py-2"
                        style="border-radius: 50px;">
                        {{ $medico->usuario->nombre }} {{ $medico->usuario->apellido }}
                    </span>
                @endif

                <p class="text-muted small px-3 mt-2">
                    {{ isset($medico->id)
                    ? __('Modifique los campos necesarios para actualizar el perfil profesional del médico.')
                    : __('Vincule una cuenta de usuario existente con su especialidad y código MINSA.') 
                    }}
                </p>
            </div>

            {{-- Sección de Campos del Formulario --}}
            <div class="col-md-8">
                <div class="row">
                    {{-- Usuario del Sistema --}}
                    <div class="form-group col-md-12">
                        <label for="usuario_id" class="text-muted small uppercase font-weight-bold">
                            <i
                                class="fas fa-user-circle mr-1 {{ isset($medico->id) ? 'text-success' : 'text-primary' }}"></i>
                            {{ __('Usuario del Sistema') }}
                        </label>
                        <select name="usuario_id" id="usuario_id"
                            class="form-control shadow-sm @error('usuario_id') is-invalid @enderror"
                            style="border-radius: 10px;">
                            <option value="">Selecciona un Usuario...</option>
                            @foreach ($usuarios as $id => $nombre)
                                <option value="{{ $id }}" {{ old('usuario_id', $medico?->usuario_id) == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_id')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Especialidad --}}
                    <div class="form-group col-md-6">
                        <label for="especialidad_id" class="text-muted small uppercase font-weight-bold">
                            <i
                                class="fas fa-stethoscope mr-1 {{ isset($medico->id) ? 'text-success' : 'text-primary' }}"></i>
                            {{ __('Especialidad') }}
                        </label>
                        <select name="especialidad_id" id="especialidad_id"
                            class="form-control shadow-sm @error('especialidad_id') is-invalid @enderror"
                            style="border-radius: 10px;">
                            <option value="">Selecciona Especialidad...</option>
                            @foreach ($especialidades as $id => $nombre)
                                <option value="{{ $id }}" {{ old('especialidad_id', $medico?->especialidad_id) == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('especialidad_id')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Código MINSA --}}
                    <div class="form-group col-md-6">
                        <label for="codigo_minsa" class="text-muted small uppercase font-weight-bold">
                            <i
                                class="fas fa-id-card mr-1 {{ isset($medico->id) ? 'text-success' : 'text-primary' }}"></i>
                            {{ __('Código MINSA') }}
                        </label>
                        <input type="text" name="codigo_minsa" id="codigo_minsa"
                            class="form-control shadow-sm @error('codigo_minsa') is-invalid @enderror"
                            value="{{ old('codigo_minsa', $medico?->codigo_minsa) }}" maxlength="6"
                            style="border-radius: 10px; font-family: monospace; font-weight: bold;">
                        @error('codigo_minsa')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Descripción Profesional --}}
                    <div class="form-group col-md-12">
                        <label for="descripcion" class="text-muted small uppercase font-weight-bold">
                            <i
                                class="fas fa-file-alt mr-1 {{ isset($medico->id) ? 'text-success' : 'text-primary' }}"></i>
                            {{ __('Descripción Profesional') }}
                        </label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                            class="form-control shadow-sm @error('descripcion') is-invalid @enderror"
                            style="border-radius: 10px;">{{ old('descripcion', $medico?->descripcion) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer con Inversión de Colores --}}
    <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4"
        style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
        <a href="{{ route('medico.index') }}" class="btn btn-invert mr-3 px-4 d-flex align-items-center shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> {{ __('Regresar') }}
        </a>

        <button type="submit"
            class="btn {{ isset($medico->id) ? 'btn-success-invert' : 'btn-primary-invert' }} px-5 d-flex align-items-center shadow-sm">
            <i class="fas {{ isset($medico->id) ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i>
            {{ isset($medico->id) ? __('Actualizar Información') : __('Guardar Médico') }}
        </button>
    </div>
</div>

@push('css')
    <style>
        /* Estilos de Inversión */
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

        /* Focus Dinámico */
        .card .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.2rem
                {{ isset($medico->id) ? 'rgba(40, 167, 69, 0.15)' : 'rgba(0, 123, 255, 0.15)' }}
                !important;
            border-color:
                {{ isset($medico->id) ? '#28a745' : '#80bdff' }}
                !important;
        }
    </style>
@endpush