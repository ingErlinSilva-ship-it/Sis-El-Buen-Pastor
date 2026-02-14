@php
    $esEdicion = isset($cita->id);
    $esAtencion = request()->routeIs('cita.atender*'); 
    $esVisualizacion = request()->routeIs('cita.show*');

    // Lógica de colores según el estado
    $temaColor = '#007bff'; // Azul - Crear
    $temaFondoIcono = '#e7f1ff';
    $btnClase = 'btn-primary-invert';

    if($esVisualizacion) {
        $temaColor = '#6f42c1'; // Púrpura - Ver
        $temaFondoIcono = '#f3e5f5';
        $btnClase = 'btn-purple-invert';

    } elseif($esAtencion) {
        $temaColor = '#fd7e14'; // Naranja - Atender
        $temaFondoIcono = '#fff3e0';
        $btnClase = 'btn-warning-invert';

    } elseif($esEdicion) {
        $temaColor = '#28a745'; // Verde - Editar
        $temaFondoIcono = '#e8f5e9';
        $btnClase = 'btn-success-invert';
    }

    $sombraFocus = $temaColor . '40'; 
@endphp

<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                
                {{-- ENCABEZADO CON FRANJA DINÁMICA --}}
                <div class="card-header bg-white border-bottom py-3 px-4" 
                    style="border-top: 5px solid {{ $temaColor }}; border-radius: 15px 15px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" 
                        style="background-color: {{ $temaFondoIcono }}; width: 45px; height: 45px;">
                            <i class="fas {{ $esEdicion ? 'fa-calendar-check' : ($esAtencion ? 'fa-user-md' : 'fa-calendar-plus') }}" 
                               style="color: {{ $temaColor }}"></i>
                        </div>
                        <div>
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                @if($esVisualizacion) {{ __('Detalle de la Cita Médica') }}
                                @elseif($esAtencion) {{ __('Atención de Cita Médica') }}
                                @elseif($esEdicion) {{ __('Actualizar Cita Médica') }}
                                @else {{ __('Programar Nueva Cita') }}
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    {{-- SECCIÓN 1: IDENTIFICACIÓN DEL PACIENTE --}}
                    <div class="mb-5">
                        <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: {{ $temaColor }}; letter-spacing: 1px;">
                            <i class="fas fa-id-card-alt mr-1"></i> 1. Verificación del Paciente
                        </h6>
                        
                        <div class="form-group mb-0 p-4 bg-light shadow-sm" style="border-radius: 12px; border-left: 5px solid {{ $temaColor }};">
                            <label class="small font-weight-bold text-dark">Buscar por Cédula</label>
                            <div class="input-group">
                                <input type="text" id="buscar_cedula" class="form-control border-right-0 shadow-none @if($esVisualizacion || $esAtencion) bg-disabled @endif" placeholder="001-000000-0000A" style="border-radius: 8px 0 0 8px;" @if($esVisualizacion || $esAtencion) readonly @endif>
                                <div class="input-group-append">
                                    <button class="btn btn-info-invert px-4 shadow-sm" type="button" id="btn_consultar" @if($esVisualizacion || $esAtencion) disabled @endif style="border-radius: 0 8px 8px 0; font-weight: bold;">
                                        <i class="fa fa-search mr-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cedula_buscada" id="cedula_buscada">

                    {{-- BLOQUE: PACIENTE ENCONTRADO --}}
                    <div id="seccion_paciente_existente" style="display: none; border-radius: 12px; border-left: 5px solid {{ $temaColor }};" 
                        class="p-4 mb-5 bg-light border shadow-sm animate__animated animate__fadeIn">
                        <label class="font-weight-bold small mb-3" style="color: {{ $temaColor }};">
                            <i class="fas fa-check-circle mr-1"></i> PACIENTE SELECCIONADO
                        </label>

                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase font-weight-bold">Nombre</small>
                                <input type="text" id="nombre_paciente_ex" class="form-control shadow-sm font-weight text-dark" readonly style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase font-weight-bold">Apellido</small>
                                <input type="text" id="apellido_paciente_ex" class="form-control shadow-sm font-weight text-dark" readonly style="border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="paciente_id" id="paciente_id_hidden" value="{{ $cita->paciente_id ?? '' }}">

                    {{-- SECCIÓN 2: DETALLES DE LA CONSULTA --}}
                    <div class="mb-2">
                        <h6 class="text-uppercase font-weight-bold mb-4" style="font-size: 0.75rem; color: {{ $temaColor }}; letter-spacing: 1px;">
                            <i class="fas fa-stethoscope mr-1"></i> 2. Información de la Consulta
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 form-group mb-4">
                                <label for="medico_id" class="small font-weight-bold text-muted text-uppercase">Médico Asignado</label>
                                <select name="medico_id" id="medico_id_select" class="form-control select2 shadow-sm bloqueable" disabled>
                                    <option value="">Seleccione un Médico</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico['id'] }}" data-especialidad="{{ $medico['especialidad'] }}" {{ old('medico_id', $cita?->medico_id) == $medico['id'] ? 'selected' : '' }}>
                                            {{ $medico['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Especialidad: Siempre readonly para que no se altere manualmente --}}
                            <div class="col-md-6 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Especialidad</label>
                                <input type="text" id="especialidad_display" class="form-control shadow-sm bg-disabled font-weight-bold"  disabled readonly style="border-radius: 8px;">
                            </div>

                            <div class="col-md-4 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Fecha de Cita</label>
                                <input type="date" name="fecha" id="fecha" class="form-control shadow-sm bloqueable bg-disabled @error('fecha') is-invalid @enderror" 
                                value="{{ old('fecha', $cita?->fecha) }}" @if(!$cita->exists) min="{{ date('Y-m-d') }}" @endif disabled style="border-radius: 8px;">
                            </div>

                            <div class="col-md-4 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Hora</label>
                                <input type="time" name="hora" id="hora" class="form-control shadow-sm bloqueable bg-disabled @error('hora') is-invalid @enderror" 
                                value="{{ old('hora', $cita?->hora) }}" disabled style="border-radius: 8px;">
                            </div>

                            <div class="col-md-4 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Duración (Min)</label>
                                <input type="number" name="duracion_minutos" id="duracion_minutos" class="form-control shadow-sm bloqueable bg-disabled" 
                                value="{{ old('duracion_minutos', $cita?->duracion_minutos ?? 30) }}" disabled style="border-radius: 8px;">
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Estado Inicial</label>
                                <select name="estado" class="form-control shadow-sm bloqueable" disabled style="border-radius: 8px;">
                                    <option value="pendiente" {{ old('estado', $cita?->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmada" {{ old('estado', $cita?->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="asistida" {{ old('estado', $cita?->estado) == 'asistida' ? 'selected' : '' }}>Finalizada</option>
                                    <option value="cancelada" {{ old('estado', $cita?->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group mb-0">
                                <label class="small font-weight-bold text-muted text-uppercase">Motivo de la Cita</label>
                                <textarea name="motivo" id="motivo" class="form-control shadow-sm bloqueable bg-disabled" disabled rows="3" placeholder="Describa brevemente el síntoma o motivo..." style="border-radius: 10px;">{{ old('motivo', $cita?->motivo) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- CAMPOS TÉCNICOS OCULTOS --}}
                    <input type="hidden" name="origen" value="{{ $cita?->origen ?? 'presencial' }}">
                    <input type="hidden" name="chat_session_id" value="{{ $cita?->chat_session_id }}">
                    <input type="hidden" name="token_confirmacion" value="{{ $cita?->token_confirmacion }}">
                </div>

                {{-- PIE DE PÁGINA --}}
                <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 15px 15px;">
                    <a href="{{ route('cita.index') }}" class="btn btn-invert mr-3 px-4 shadow-sm">
                        <i class="fas fa-times-circle mr-2"></i> {{ __('Cancelar') }}
                    </a>
                    
                    @if(!$esVisualizacion)
                        <button type="submit" class="btn {{ $btnClase }} px-5 shadow-sm font-weight-bold">
                            <i class="fas {{ $esAtencion ? 'fa-user-md' : ($esEdicion ? 'fa-sync-alt' : 'fa-save') }} mr-2"></i> 
                            {{ $esAtencion ? 'Atender Cita' : ($esEdicion ? 'Actualizar Cita' : 'Guardar Cita') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    /* 1. ESTADO BLOQUEADO Y HABILITADO */
    .bg-disabled, 
    .form-control:disabled, 
    .form-control[readonly] {
        background-color: #f2f2f2 !important;
        color: #495057 !important;
        border: 1px solid #dee2e6 !important;
        opacity: 1 !important;
    }

    .form-control:not(:disabled):not([readonly]) {
        background-color: #ffffff !important;
    }

    /* 2. BASE DE INVERSIÓN GENERAL (BOTONES DE ABAJO) */
    .btn-invert, .btn-primary-invert, .btn-success-invert, .btn-purple-invert, .btn-warning-invert {
        background-color: #ffffff !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
        border-width: 2px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* 3. BOTÓN DE BUSCAR */
    .btn-info-invert {
        background-color: #ffffff !important;
        border: 2px solid #17a2b8 !important;
        color: #17a2b8 !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        
        border-radius: 0 8px 8px 0 !important; 
        height: 100%;
        display: flex;
        align-items: center;
    }

    .btn-info-invert:hover {
        background-color: #17a2b8 !important;
        color: #ffffff !important;
    }

    /* 4. COLORES DE INVERSIÓN (FOOTER) */
    .btn-invert { border: 2px solid #343a40 !important; color: #343a40 !important; }
    .btn-invert:hover { background-color: #343a40 !important; color: #ffffff !important; }

    .btn-primary-invert { border: 2px solid #007bff !important; color: #007bff !important; }
    .btn-primary-invert:hover { background-color: #007bff !important; color: #ffffff !important; }

    .btn-success-invert { border: 2px solid #28a745 !important; color: #28a745 !important; }
    .btn-success-invert:hover { background-color: #28a745 !important; color: #ffffff !important; }

    .btn-warning-invert { border: 2px solid #fd7e14 !important; color: #fd7e14 !important; }
    .btn-warning-invert:hover { background-color: #fd7e14 !important; color: #ffffff !important; }

    .btn-purple-invert { border: 2px solid #6f42c1 !important; color: #6f42c1 !important; }
    .btn-purple-invert:hover { background-color: #6f42c1 !important; color: #ffffff !important; }

    /* Evitar que el icono cambie a colores raros en hover */
    .btn:hover i { color: #ffffff !important; }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem {{ $sombraFocus }} !important;
        border-color: {{ $temaColor }} !important;
    }
</style>
@endpush