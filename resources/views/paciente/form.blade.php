<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- USUARIO --}}
        <div class="form-group mb-2 mb20">
                <label for="usuario_id">Paciente</label> 
                <select name="usuario_id"
                    class="form-control @error('usuario_id') is-invalid @enderror">
                    <option value="">Seleccione un usuario</option>

                    @foreach ($usuarios as $id => $nombre)
                        <option value="{{ $id }}"
                            {{ old('usuario_id', $paciente?->usuario_id) == $id ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>

                {!! $errors->first('usuario_id',
                '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        {{-- Si el paciente es Menor de edad --}}
        <div class="form-group mb-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="es_menor" class="custom-control-input" id="es_menor" value="1" {{ old('es_menor', $paciente->es_menor) == '1' ? 'checked' : ''}}>
            <label class="custom-control-label font-weight-bold text-primary" for="es_menor">
                <i class="fas fa-child mr-1"></i> Registrar como menor de edad / recién nacido
            </label>
        </div>
    </div>

    <div id="seccion_tutor" style="display: none; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem;" class="p-3 mb-4 shadow-sm">
        <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: #64748b;">
            <i class="fas fa-user-shield mr-1"></i> Datos del Responsable (Madre, Padre o Tutor)
        </h6>

        <div class="row">
            <div class="col-md-6 form-group">
                <label>Nombre del Responsable</label>
                <input type="text" name="tutor_nombre" class="form-control" value="{{ old('tutor_nombre', $paciente->tutor_nombre) }}" class="form-control @error('tutor_nombre') is-invalid @enderror" placeholder="Nombres">
                @error('tutor_nombre')
                    <span class="invalid-feedback" style="display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label>Apellido del Responsable</label>
                <input type="text" name="tutor_apellido" class="form-control" value="{{ old('tutor_apellido', $paciente->tutor_apellido) }}" class="form-control @error('tutor_nombre') is-invalid @enderror" placeholder="Apellidos">
                @error('tutor_apellido')
                    <span class="invalid-feedback" style="display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>Cédula del Tutor</label>
                <input type="text" name="tutor_cedula" id="tutor_cedula" class="form-control" value="{{ old('tutor_cedula', $paciente->tutor_cedula) }}" class="form-control @error('tutor_cedula') is-invalid @enderror" placeholder="000-000000-0000X">
                @error('tutor_cedula')
                    <span class="invalid-feedback" style="display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label>Teléfono de Contacto</label>
                <input type="text" name="tutor_telefono" id="tutor_telefono" class="form-control" value="{{ old('tutor_telefono', $paciente->tutor_telefono) }}" 
                maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control @error('tutor_telefono') is-invalid @enderror" placeholder="Ej: 8888-8888">
                @error('tutor_telefono')
                    <span class="invalid-feedback" style="display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4 form-group">
                <label>Parentesco</label>
                <select name="tutor_parentesco" class="form-control @error('tutor_parentesco') is-invalid @enderror">
                    <option value="">Seleccione...</option>
                    <option value="Padre" {{ old('tutor_parentesco', $paciente->tutor_parentesco) == 'Padre' ? 'selected' : '' }}>Padre</option>
                    <option value="Madre" {{ old('tutor_parentesco', $paciente->tutor_parentesco) == 'Madre' ? 'selected' : '' }}>Madre</option>
                    <option value="Tutor Legal" {{ old('tutor_parentesco', $paciente->tutor_parentesco) == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                </select>
                @error('tutor_parentesco')
                    <span class="invalid-feedback" style="display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

        <div class="form-group mb-2 mb20">
            <label for="fecha_nacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
            <input type="date" name="fecha_nacimiento" 
            class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
            value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('Y-m-d') : '') }}" 
            id="fecha_nacimiento" max="{{ date('Y-m-d') }}">
            {!! $errors->first('fecha_nacimiento', 
            '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="cedula" class="form-label">{{ __('Cédula') }}</label>
            <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $paciente?->cedula) }}" id="cedula" class="form-control @error('cedula') is-invalid @enderror" placeholder="001-000000-0000A">

                @error('cedula')
                    <span class="invalid-feedback" style="display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion" class="form-label">{{ __('Dirección') }}</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $paciente?->direccion) }}" id="direccion" placeholder="Dirección">
            {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        {{-- TIPO DE SANGRE --}}
        <div class="form-group mb-2 mb20">
            <div class="form-group">
                <label for="tipo_sangre">Tipo de sangre</label>
                <select name="tipo_sangre" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $tipo)
                        <option value="{{ $tipo }}"
                            {{ old('tipo_sangre', $paciente->tipo_sangre ?? '') == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group mb-2 mb20">
            <h5><i class="fas fa-allergies"></i> Alergias</h5>

            @foreach ($alergias as $id => $nombre)
                <div class="form-check">
                <input class="form-check-input" type="checkbox" name="alergias[]" value="{{ $id }}"
                {{ in_array($id,old('alergias', $paciente->alergias?->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                        {{ $nombre }}
                    </label>
                </div>
            @endforeach     
        </div>
        
        <div class="form-group mb-2 mb20">
            <h5><i class="fas fa-notes-medical"></i> Enfermedades</h5>

            @foreach ($enfermedades as $id => $nombre)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="enfermedades[]" value="{{ $id }}"
                        {{ in_array($id, old('enfermedades', $paciente->enfermedades?->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label">
                        {{ $nombre }}
                    </label>
                </div>
            @endforeach   
        </div>
        

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>