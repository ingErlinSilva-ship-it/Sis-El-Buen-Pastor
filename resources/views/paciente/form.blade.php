@php
    $esEdicion = isset($paciente->id);
    $temaColor = $esEdicion ? '#28a745' : '#007bff';
    $temaFondoIcono = $esEdicion ? '#e8f5e9' : '#e7f1ff';
    $sombraFocus = $esEdicion ? 'rgba(40, 167, 69, 0.25)' : 'rgba(0, 123, 255, 0.25)';
@endphp

<div class="container-fluid pt-4">
    <div class="col-12">
        {{-- Card Principal Unificada --}}
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            
            {{-- Header Estilo Usuarios --}}
            <div class="card-header bg-white border-bottom py-3 px-4" style="border-top: 5px solid {{ $temaColor }}; border-radius: 15px 15px 0 0;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="background-color: {{ $temaFondoIcono }}; width: 45px; height: 45px;">
                        <i class="fas {{ $esEdicion ? 'fa-user-edit text-success' : 'fa-user-plus text-primary' }}"></i>
                    </div>
                    <div>
                        <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                            {{ $esEdicion ? __('Actualizar Datos del Paciente') : __('Registro de Nuevo Paciente') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {{-- Bloque 1: Cuenta de Usuario --}}
                <div class="mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: #3498db; letter-spacing: 1px;">
                        <i class="fas fa-id-badge mr-1"></i> 1. Cuenta de Acceso
                    </h6>
                    <div class="form-group mb-0 p-3 bg-light" style="border-radius: 10px; border-left: 4px solid #3498db;">
                        <label for="usuario_id" class="small font-weight-bold">Paciente vinculado al sistema</label> 
                        <select name="usuario_id" id="usuario_id" class="form-control select2 @error('usuario_id') is-invalid @enderror">
                            <option value="">Seleccione un usuario...</option>
                            @foreach ($usuarios as $id => $nombre)
                                <option value="{{ $id }}" {{ old('usuario_id', $paciente?->usuario_id) == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                {{-- Bloque 2: Condición Especial --}}
                <div class="mb-4 p-3 d-flex align-items-center" style="background-color: #f0f7ff; border-radius: 10px;">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="es_menor" class="custom-control-input" id="es_menor" value="1" {{ old('es_menor', $paciente->es_menor) == '1' ? 'checked' : ''}}>
                        <label class="custom-control-label font-weight-bold text-primary" for="es_menor" style="cursor: pointer;">
                            <i class="fas fa-child mr-1"></i> ¿El paciente es menor de edad o recién nacido?
                        </label>
                    </div>
                </div>

                {{-- Bloque 3: Datos del Tutor (Dinámico) --}}
                <div id="seccion_tutor" style="display: none; border-radius: 10px; border-left: 4px solid #6c757d;" class="p-3 mb-4 bg-light border">
                    <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.7rem; color: #64748b;">
                        <i class="fas fa-user-shield mr-1"></i> Datos del Responsable
                    </h6>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Nombre del Responsable</label>
                            <input type="text" name="tutor_nombre" class="form-control @error('tutor_nombre') is-invalid @enderror" value="{{ old('tutor_nombre', $paciente->tutor_nombre) }}" placeholder="Nombres">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="small font-weight-bold">Apellido del Responsable</label>
                            <input type="text" name="tutor_apellido" class="form-control @error('tutor_apellido') is-invalid @enderror" value="{{ old('tutor_apellido', $paciente->tutor_apellido) }}" placeholder="Apellidos">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="small font-weight-bold">Cédula del Paciente</label>
                            <input type="text" name="tutor_cedula" id="tutor_cedula" 
                                class="form-control @error('tutor_cedula') is-invalid @enderror" 
                                value="{{ old('tutor_cedula', $paciente->cedula) }}" 
                                placeholder="001-000000-0000A">
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="small font-weight-bold">Teléfono</label>
                            <input type="text" name="tutor_telefono" id="tutor_telefono" class="form-control @error('tutor_telefono') is-invalid @enderror" 
                                value="{{ old('tutor_telefono', $paciente->tutor_telefono) }}" 
                                placeholder="88888888"
                                maxlength="8" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="small font-weight-bold">Parentesco</label>
                            <select name="tutor_parentesco" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Padre" {{ old('tutor_parentesco', $paciente->tutor_parentesco) == 'Padre' ? 'selected' : '' }}>Padre</option>
                                <option value="Madre" {{ old('tutor_parentesco', $paciente->tutor_parentesco) == 'Madre' ? 'selected' : '' }}>Madre</option>
                                <option value="Tutor Legal" {{ old('tutor_parentesco', $paciente->tutor_parentesco) == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Bloque 4: Información General --}}
                <div class="mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: #2ecc71; letter-spacing: 1px;">
                        <i class="fas fa-file-alt mr-1"></i> 2. Información General
                    </h6>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="small font-weight-bold">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="small font-weight-bold">Cédula del Paciente</label>
                            <input type="text" name="cedula" id="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula', $paciente->cedula) }}" placeholder="001-000000-0000A">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="small font-weight-bold">Tipo de Sangre</label>
                            <select name="tipo_sangre" class="form-control">
                                <option value="">Seleccione</option>
                                @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $tipo)
                                    <option value="{{ $tipo }}" {{ old('tipo_sangre', $paciente->tipo_sangre) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group mb-0">
                            <label class="small font-weight-bold">Dirección de Domicilio</label>
                            <textarea name="direccion" class="form-control" rows="2" placeholder="Dirección exacta...">{{ old('direccion', $paciente->direccion) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Bloque 5: Antecedentes (Lado a Lado) --}}
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: #e74c3c; letter-spacing: 1px;">
                            <i class="fas fa-allergies mr-1"></i> Alergias
                        </h6>
                        <div class="p-3 border rounded bg-light shadow-sm" style="max-height: 150px; overflow-y: auto;">
                            @foreach ($alergias as $id => $nombre)
                                <div class="custom-control custom-checkbox mb-1">
                                    <input class="custom-control-input" type="checkbox" name="alergias[]" id="alergia_{{ $id }}" value="{{ $id }}"
                                    {{ in_array($id, old('alergias', $paciente->alergias?->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label small" for="alergia_{{ $id }}">{{ $nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: #f39c12; letter-spacing: 1px;">
                            <i class="fas fa-notes-medical mr-1"></i> Enfermedades
                        </h6>
                        <div class="p-3 border rounded bg-light shadow-sm" style="max-height: 150px; overflow-y: auto;">
                            @foreach ($enfermedades as $id => $nombre)
                                <div class="custom-control custom-checkbox mb-1">
                                    <input class="custom-control-input" type="checkbox" name="enfermedades[]" id="enfermedad_{{ $id }}" value="{{ $id }}"
                                    {{ in_array($id, old('enfermedades', $paciente->enfermedades?->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label small" for="enfermedad_{{ $id }}">{{ $nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 15px 15px;">
                <a href="{{ route('paciente.index') }}" class="btn btn-outline-secondary mr-2 px-4 shadow-sm" style="border-radius: 8px;">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit" class="btn {{ $esEdicion ? 'btn-success' : 'btn-primary' }} px-4 shadow-sm" style="border-radius: 8px; font-weight: bold;">
                    <i class="fas {{ $esEdicion ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i> {{ $esEdicion ? 'Actualizar Paciente' : 'Guardar Paciente' }}
                </button>
            </div>
        </div>
    </div>
</div>


@push('css')
<style>
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem {{ $sombraFocus }} !important;
        border-color: {{ $temaColor }} !important;
    }
    .btn-group-toggle .btn.active {
        box-shadow: none !important;
        background-color: {{ $temaColor == '#28a745' ? '#28a745' : '#007bff' }};
        color: #fff !important;
    }


</style>
@endpush