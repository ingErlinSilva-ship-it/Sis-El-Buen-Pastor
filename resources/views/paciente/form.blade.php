<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- USUARIO --}}
        <div class="form-group mb-2 mb20">
                <label for="usuario_id">Usuario</label> 
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
            <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula', $paciente?->cedula) }}" id="cedula" placeholder="001-000000-0000A">

            @error('cedula')
                <span class="invalid-feedback" role="alert">
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