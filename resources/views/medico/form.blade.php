<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="usuario_id" class="form-label">{{ __('Usuario') }}</label>
            
            <select name="usuario_id" class="form-control @error('usuario_id') is-invalid @enderror" id="usuario_id">
                <option value="">Selecciona un Usuario</option>
                {{-- Genera las opciones usando los datos pasados desde el controlador --}}
                @foreach ($usuarios as $id => $nombre)
                    <option value="{{ $id }}" {{ old('usuario_id', $medico?->usuario_id) == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
            
            {!! $errors->first('usuario_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="especialidad_id" class="form-label">{{ __('Especialidad') }}</label>
            
            <select name="especialidad_id" class="form-control @error('especialidad_id') is-invalid @enderror" id="especialidad_id">
                <option value="">Selecciona una Especialidad</option>

                {{-- Genera las opciones usando los datos pasados desde el controlador --}}
                @foreach ($especialidades as $id => $nombre)
                    <option value="{{ $id }}" {{ old('especialidad_id', $medico?->especialidad_id) == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>

            {!! $errors->first('especialidad_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="codigo_minsa" class="form-label">{{ __('Codigo Minsa') }}</label>
            <input type="text" name="codigo_minsa" class="form-control @error('codigo_minsa') is-invalid @enderror" value="{{ old('codigo_minsa', $medico?->codigo_minsa) }}" id="codigo_minsa" placeholder="Codigo Minsa">
            {!! $errors->first('codigo_minsa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $medico?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>