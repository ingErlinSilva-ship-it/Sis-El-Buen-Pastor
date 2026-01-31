@php
    $esEdicion = isset($cita->id);
    $temaColor = $esEdicion ? '#28a745' : '#007bff';
    $temaFondoIcono = $esEdicion ? '#e8f5e9' : '#e7f1ff';
    $sombraFocus = $esEdicion ? 'rgba(40, 167, 69, 0.25)' : 'rgba(0, 123, 255, 0.25)';
@endphp

<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            {{-- TARJETA PRINCIPAL RESALTADA --}}
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                
                {{-- ENCABEZADO UNIFICADO --}}
                <div class="card-header bg-white border-bottom py-3 px-4" 
                    style="border-top: 5px solid {{ $temaColor }}; border-radius: 15px 15px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" 
                        style="background-color: {{ $temaFondoIcono }}; width: 45px; height: 45px;">
                            <i class="fas {{ $esEdicion ? 'fa-calendar-check text-success' : 'fa-calendar-plus text-primary' }}"></i>
                        </div>
                        <div>
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                {{ $esEdicion ? __('Actualizar Cita Médica') : __('Programar Nueva Cita') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    {{-- SECCIÓN 1: IDENTIFICACIÓN DEL PACIENTE --}}
                    <div class="mb-5">
                        <h6 class="text-uppercase font-weight-bold mb-3" style="font-size: 0.75rem; color: #3498db; letter-spacing: 1px;">
                            <i class="fas fa-id-card-alt mr-1"></i> 1. Verificación del Paciente
                        </h6>
                        
                        <div class="form-group mb-0 p-4 bg-light shadow-sm" style="border-radius: 12px; border-left: 5px solid #3498db;">
                            <label class="small font-weight-bold text-dark">Buscar por Cédula</label>
                            <div class="input-group">
                                <input type="text" id="buscar_cedula" class="form-control border-right-0 shadow-none" placeholder="001-000000-0000A" style="border-radius: 8px 0 0 8px;">
                                <div class="input-group-append">
                                    <button class="btn btn-info px-4 shadow-sm" type="button" id="btn_consultar" style="border-radius: 0 8px 8px 0;">
                                        <i class="fa fa-search mr-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cedula_buscada" id="cedula_buscada">

                    {{-- BLOQUE: PACIENTE ENCONTRADO (DINÁMICO) --}}
                    <div id="seccion_paciente_existente" style="display: none; border-radius: 12px; border-left: 5px solid #28a745;" 
                        class="p-4 mb-5 bg-light border shadow-sm animate__animated animate__fadeIn">
                        <label class="text-success font-weight-bold small mb-3">
                            <i class="fas fa-check-circle mr-1"></i> PACIENTE SELECCIONADO
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase font-weight-bold">Nombre</small>
                                <input type="text" id="nombre_paciente_ex" class="form-control border-0 bg-transparent p-0 font-weight-bold text-dark" readonly style="font-size: 1.1rem;">
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase font-weight-bold">Apellido</small>
                                <input type="text" id="apellido_paciente_ex" class="form-control border-0 bg-transparent p-0 font-weight-bold text-dark" readonly style="font-size: 1.1rem;">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="paciente_id" id="paciente_id_hidden" value="{{ $cita->paciente_id ?? '' }}">

                    {{-- BLOQUE: NUEVO PACIENTE (DINÁMICO) --}}
                    <div id="seccion_registro_nuevo" style="display: none; border-radius: 12px; border: 2px dashed #007bff;" 
                        class="p-4 mb-5 bg-white shadow-sm animate__animated animate__slideInDown">
                        <h6 class="text-uppercase font-weight-bold mb-4 text-primary">
                            <i class="fas fa-user-plus mr-1"></i> Nuevo Registro Rápido
                        </h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Nombre</label>
                                <input type="text" name="nombre" id="nombre_paciente" class="form-control shadow-sm" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Apellido</label>
                                <input type="text" name="apellido" id="apellido_paciente" class="form-control shadow-sm" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Celular</label>
                                <input type="text" name="celular" id="celular_paciente" class="form-control shadow-sm" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Correo Electrónico</label>
                                <input type="email" name="email" id="email_paciente" class="form-control shadow-sm" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="button" id="btn_guardar_paciente_rapido" class="btn btn-primary btn-block shadow font-weight-bold py-2" style="border-radius: 10px;">
                                    <i class="fas fa-user-check mr-1"></i> GUARDAR Y VINCULAR PACIENTE
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: DETALLES DE LA CONSULTA --}}
                    <div class="mb-2">
                        <h6 class="text-uppercase font-weight-bold mb-4" style="font-size: 0.75rem; color: #2ecc71; letter-spacing: 1px;">
                            <i class="fas fa-stethoscope mr-1"></i> 2. Información de la Consulta
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 form-group mb-4">
                                <label for="medico_id" class="small font-weight-bold text-muted text-uppercase">Médico Asignado</label>
                                <select name="medico_id" id="medico_id_select" class="form-control select2 shadow-sm bloqueable" disabled>
                                    <option value="">Seleccione un Médico</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico['id'] }}" data-especialidad="{{ $medico['especialidad'] }}">
                                            {{ $medico['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Especialidad</label>
                                <input type="text" id="especialidad_display" class="form-control shadow-sm bg-light font-weight-bold" readonly style="border-radius: 8px;">
                            </div>

                            <div class="col-md-4 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Fecha de Cita</label>
                                <input type="date" name="fecha" id="fecha" class="form-control shadow-sm bloqueable @error('fecha') is-invalid @enderror" 
                                value="{{ old('fecha', $cita?->fecha) }}" disabled min="{{ date('Y-m-d') }}" style="border-radius: 8px;">
                            </div>

                            <div class="col-md-4 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Hora</label>
                                <input type="time" name="hora" id="hora" class="form-control shadow-sm bloqueable @error('hora') is-invalid @enderror" 
                                value="{{ old('hora', $cita?->hora) }}" disabled style="border-radius: 8px;">
                            </div>

                            <div class="col-md-4 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Duración (Min)</label>
                                <input type="number" name="duracion_minutos" id="duracion_minutos" class="form-control shadow-sm bloqueable" 
                                value="{{ old('duracion_minutos', $cita?->duracion_minutos ?? 30) }}" disabled style="border-radius: 8px;">
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Estado Inicial</label>
                                <select name="estado" class="form-control shadow-sm bloqueable" style="border-radius: 8px;">
                                    <option value="pendiente" {{ old('estado', $cita?->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmada" {{ old('estado', $cita?->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="asistida" {{ old('estado', $cita?->estado) == 'asistida' ? 'selected' : '' }}>Finalizada</option>
                                    <option value="cancelada" {{ old('estado', $cita?->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group mb-0">
                                <label class="small font-weight-bold text-muted text-uppercase">Motivo de la Cita</label>
                                <textarea name="motivo" id="motivo" class="form-control shadow-sm bloqueable" disabled rows="3" placeholder="Describa brevemente el síntoma o motivo..." 
                                style="border-radius: 10px;">{{ old('motivo', $cita?->motivo) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- CAMPOS TÉCNICOS OCULTOS --}}
                    <input type="hidden" name="origen" value="{{ $cita?->origen ?? 'presencial' }}">
                    <input type="hidden" name="chat_session_id" value="{{ $cita?->chat_session_id }}">
                    <input type="hidden" name="token_confirmacion" value="{{ $cita?->token_confirmacion }}">
                </div>

                {{-- PIE DE PÁGINA: ACCIONES --}}
                <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 15px 15px;">
                    <a href="{{ route('cita.index') }}" class="btn btn-outline-secondary mr-3 px-4 shadow-sm" style="border-radius: 10px; font-weight: 600;">
                        <i class="fas fa-times-circle mr-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn {{ $esEdicion ? 'btn-success' : 'btn-primary' }} px-5 shadow-sm" style="border-radius: 10px; font-weight: 800;">
                        <i class="fas {{ $esEdicion ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i> {{ $esEdicion ? 'ACTUALIZAR CITA' : 'GUARDAR CITA MÉDICA' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    /* Estilo de enfoque dinámico */
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem {{ $sombraFocus }} !important;
        border-color: {{ $temaColor }} !important;
        transition: all 0.2s ease;
    }
    
    /* Clase personalizada para el borde izquierdo de nuevos registros */
    .border-left-4-primary {
        border-left: 5px solid #007bff !important;
    }

    /* Ajustes para Select2 unificados */
    .select2-container--default .select2-selection--single {
        border-radius: 8px !important;
        height: calc(2.25rem + 2px) !important;
        border: 1px solid #ced4da !important;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
    }

    /* Animaciones sutiles */
    .animate__animated {
        --animate-duration: 0.5s;
    }
</style>
@endpush