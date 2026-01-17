<div class="row padding-1 p-1">
    <div class="col-md-12">

        {{-- BUSCADOR CORREGIDO --}}
        <div class="form-group mb-4" style="background: #f4f6f9; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <label><strong>Verificar Paciente por Cédula</strong></label>
            <div class="input-group">
                {{-- CAMBIO: ID="buscar_cedula" --}}
                <input type="text" id="buscar_cedula" class="form-control" placeholder="Ingrese cédula para buscar...">
                <div class="input-group-append">
                    {{-- CAMBIO: ID="btn_consultar" --}}
                    <button class="btn btn-info" type="button" id="btn_consultar">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" name="cedula_buscada" id="cedula_buscada">

        {{-- Pacientes Existentes --}}
        <div id="seccion_paciente_existente" style="display: none; background: #f0fff0; border: 1px solid #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <label class="text-success"><strong><i class="fas fa-check-circle"></i> Paciente Seleccionado</strong></label>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <small class="text-muted">Nombre</small>
                        <input type="text" id="nombre_paciente_ex" class="form-control" readonly style="font-weight: bold; background-color: #ffffff;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <small class="text-muted">Apellido</small>
                        <input type="text" id="apellido_paciente_ex" class="form-control" readonly style="font-weight: bold; background-color: #ffffff;">
                    </div>
                </div>
            </div>
        </div>

        {{-- campo oculto justo debajo --}}
        <input type="hidden" name="paciente_id" id="paciente_id_hidden" value="{{ $cita->paciente_id ?? '' }}">

        {{-- Sección de Datos del Paciente (Se expande si es nuevo) --}}
        <div id="seccion_registro_nuevo" style="display: none; background: #bcdffb; border: 1px dashed #0f406b; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h5 class=""><i class="fas fa-user-plus"></i> Nuevo Paciente Detectado</h5>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="nombre_paciente" class="form-control" placeholder="Escriba el nombre">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Apellido</label>
                    <input type="text" name="apellido" id="apellido_paciente" class="form-control" placeholder="Escriba el apellido">
                </div>
                <div class="col-md-6">
                    <label>Celular (Contraseña)</label>
                    <input type="text" name="celular" id="celular_paciente" class="form-control" placeholder="Ej: 88888888">
                </div>
                <div class="col-md-6">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" id="email_paciente" class="form-control" placeholder="paciente@ejemplo.com">
                </div>
            </div>
                <div class="col-md-12 mt20 mt-2">
                    <button type="button" id="btn_guardar_paciente_rapido" class="btn btn-primary w-100">
                        </i> Guardar Nuevo Paciente
                    </button>
                </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label for="medico_id"><strong>{{ __('Médico') }}</strong></label>
                    <select name="medico_id" id="medico_id_select" class="form-control bloqueable" disabled>
                        <option value="">Seleccione un Médico</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico['id'] }}" data-especialidad="{{ $medico['especialidad'] }}">
                                {{ $medico['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label><strong>Especialidad</strong></label>
                    {{-- Campo automático de solo lectura --}}
                    <input type="text" id="especialidad_display" class="form-control" readonly style="background-color: #f8f9fa;">
                </div>
            </div>
        </div>

        <div class="row">
            {{-- FECHA --}}
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="fecha" class="form-label"><strong>{{ __('Fecha') }}</strong></label>
                    <input type="date" name="fecha" class="form-control bloqueable @error('fecha') is-invalid @enderror" value="{{ old('fecha', $cita?->fecha) }}" id="fecha" disabled min="{{ date('Y-m-d') }}">
                    {!! $errors->first('fecha', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>
            </div>
            {{-- HORA --}}
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="hora" class="form-label"><strong>{{ __('Hora') }}</strong></label>
                    <input type="time" name="hora" class="form-control bloqueable @error('hora') is-invalid @enderror" value="{{ old('hora', $cita?->hora) }}" id="hora" disabled>
                    {!! $errors->first('hora', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            {{-- DURACIÓN --}}
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="duracion_minutos" class="form-label"><strong>{{ __('Duración (Minutos)') }}</strong></label>
                    <input type="number" name="duracion_minutos" class="form-control bloqueable @error('duracion_minutos') is-invalid @enderror" value="{{ old('duracion_minutos', $cita?->duracion_minutos ?? 30) }}" id="duracion_minutos" disabled>
                    {!! $errors->first('duracion_minutos', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>
            </div>
            {{-- ESTADO --}}
            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="estado" class="form-label"><strong>{{ __('Estado') }}</strong></label>
                    <select name="estado" class="form-control bloqueable @error('estado') is-invalid @enderror" disabled>
                        <option value="pendiente" {{ old('estado', $cita?->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ old('estado', $cita?->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="cancelada" {{ old('estado', $cita?->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    {!! $errors->first('estado', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        {{-- MOTIVO --}}
        <div class="form-group mb-2 mb20">
            <label for="motivo" class="form-label"><strong>{{ __('Motivo de la Cita') }}</strong></label>
            <textarea name="motivo" class="form-control bloqueable @error('motivo') is-invalid @enderror" disabled rows="2" id="motivo" placeholder="Ej: Dolor abdominal, chequeo general...">{{ old('motivo', $cita?->motivo) }}</textarea>
            {!! $errors->first('motivo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- CAMPOS TÉCNICOS OCULTOS (Para que recepción no los edite pero existan para el Bot) --}}
        <input type="hidden" name="origen" value="{{ $cita?->origen ?? 'presencial' }}">
        <input type="hidden" name="chat_session_id" value="{{ $cita?->chat_session_id }}">
        <input type="hidden" name="token_confirmacion" value="{{ $cita?->token_confirmacion }}">

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary w-100">{{ __('Guardar Cita Médica') }}</button>
    </div>


</div>