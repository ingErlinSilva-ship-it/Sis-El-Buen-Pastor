@php
    $esEdicion = isset($consulta->id);
    // Cambiamos el color a naranja (atender) si no es edición, o verde si es edición
    $temaColor = $esEdicion ? '#28a745' : '#fd7e14'; 
    $temaFondoIcono = $esEdicion ? '#e8f5e9' : '#fff5eb';
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
                            <i class="fas fa-user-md" style="color: {{ $temaColor }};"></i>
                        </div>
                        <div>
                            <h3 class="card-title font-weight text-dark mb-0" style="font-size: 1.2rem;">
                                {{ $esEdicion ? __('Actualizar Atención Médica') : __('Registrar Nueva Atención Médica') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Campos Ocultos --}}
                    <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id ?? $consulta->paciente_id }}">
                    <input type="hidden" name="medico_id" value="{{ $cita->medico_id ?? $consulta->medico_id }}">
                    <input type="hidden" name="cita_id" value="{{ $cita->id ?? $consulta->cita_id }}">

                    {{-- BLOQUE 1: DATOS DE LA CITA --}}
                    <div class="mb-4 p-3 bg-light rounded" style="border-left: 5px solid {{ $temaColor }};">
                        <div class="row text-center text-md-left">
                            <div class="col-md-4 border-right">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1 d-block">Paciente</label>
                                <span class="text-dark font-weight-bold">
                                    {{ $cita->paciente->usuario->nombre ?? $consulta->paciente->usuario->nombre }} 
                                    {{ $cita->paciente->usuario->apellido ?? $consulta->paciente->usuario->apellido }}
                                </span>
                            </div>
                            <div class="col-md-4 border-right">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1 d-block">Motivo inicial</label>
                                <span class="font-italic" style="color: {{ $temaColor }};">"{{ $cita->motivo ?? $consulta->cita->motivo }}"</span>
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase mb-1 d-block">Médico</label>
                                <span class="text-dark">Dr. {{ $cita->medico->usuario->nombre ?? $consulta->medico->usuario->nombre }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- BLOQUE 2: ANTECEDENTES --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded h-100" style="border-left: 4px solid #dc3545 !important;">
                                <h6 class="font-weight-bold text-danger mb-2"><i class="fas fa-exclamation-triangle"></i> Alergias</h6>
                                @forelse($alergias as $alergia)
                                    <span class="badge badge-danger px-2 py-1 shadow-xs">{{ $alergia->nombre }}</span>
                                @empty
                                    <span class="text-muted small">Sin alergias reportadas.</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="p-3 border rounded h-100" style="border-left: 4px solid #ffc107 !important;">
                                <h6 class="font-weight-bold text-warning mb-2"><i class="fas fa-pills"></i> Enfermedades Crónicas</h6>
                                @forelse($enfermedades as $enf)
                                    <span class="badge badge-warning px-2 py-1 shadow-xs">{{ $enf->nombre }}</span>
                                @empty
                                    <span class="text-muted small">Sin antecedentes.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- BLOQUE 3: SIGNOS VITALES --}}
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded shadow-xs mb-4 border">
                                <h6 class="font-weight-bold text-info border-bottom pb-2 mb-3">
                                    <i class="fas fa-heartbeat"></i> Signos Vitales
                                </h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Peso (kg)</label>
                                    <input type="number" step="0.01" name="peso" class="form-control bg-white shadow-sm @error('peso') is-invalid @enderror" placeholder="0.00" value="{{ old('peso', $consulta->peso ?? '') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Estatura (m)</label>
                                    <input type="number" step="0.01" name="estatura" class="form-control bg-white shadow-sm @error('estatura') is-invalid @enderror" placeholder="0.00" value="{{ old('estatura', $consulta->estatura ?? '') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Presión Arterial</label>
                                    <input type="text" name="presion_arterial" class="form-control bg-white shadow-sm @error('presion_arterial') is-invalid @enderror" placeholder="120/80" value="{{ old('presion_arterial', $consulta->presion_arterial ?? '') }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold">Temperatura (°C)</label>
                                    <input type="number" step="0.1" name="temperatura" class="form-control bg-white shadow-sm @error('temperatura') is-invalid @enderror" placeholder="36.5" value="{{ old('temperatura', $consulta->temperatura ?? '') }}">
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE 4: EVALUACIÓN Y RECETA --}}
                       <div class="col-md-8">
                            <div class="bg-light p-3 rounded shadow-xs mb-4 border">
                                <h6 class="font-weight-bold border-bottom pb-2 mb-3" style="color: {{ $temaColor }};">
                                    <i class="fas fa-stethoscope"></i> Evaluación y Receta
                                </h6>
                                
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Síntomas observados <span class="text-danger">*</span></label>
                                    <textarea name="sintomas" class="form-control bg-white shadow-sm @error('sintomas') is-invalid @enderror" 
                                            rows="2" placeholder="Describa los síntomas...">{{ old('sintomas', $consulta->sintomas ?? '') }}</textarea>
                                    @error('sintomas') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Diagnóstico Médico <span class="text-danger">*</span></label>
                                    <textarea name="diagnostico" class="form-control bg-white shadow-sm @error('diagnostico') is-invalid @enderror" 
                                            rows="2" placeholder="Ingrese el diagnóstico...">{{ old('diagnostico', $consulta->diagnostico ?? '') }}</textarea>
                                    @error('diagnostico') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">Prescripción (Receta) <span class="text-danger">*</span></label>
                                    <textarea name="prescripcion" class="form-control bg-white shadow-sm @error('prescripcion') is-invalid @enderror" 
                                            rows="4" placeholder="Medicamentos, dosis y duración...">{{ old('prescripcion', $consulta->prescripcion ?? '') }}</textarea>
                                    @error('prescripcion') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold">Observaciones adicionales</label>
                                    <textarea name="observaciones" class="form-control bg-white shadow-sm" 
                                            rows="2" placeholder="Notas internas o recomendaciones extra...">{{ old('observaciones', $consulta->observaciones ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PIE DE PÁGINA: ACCIONES --}}
                <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 15px 15px;">
                    {{-- Botón Cancelar con Inversión Negro --}}
                    <a href="{{ route('cita.index') }}" class="btn btn-invert mr-3 px-4 shadow-sm">
                        <i class="fas fa-times-circle mr-2"></i> Cancelar
                    </a>
                    {{-- Botón Guardar con Inversión del Tema --}}
                    <button type="submit" class="btn btn-tema-invert px-4 shadow-sm">
                        <i class="fas fa-save mr-2"></i> {{ $esEdicion ? 'Actualizar Consulta' : 'Guardar y Finalizar' }}
                    </button>
                </div>
            </div> {{-- FIN TARJETA RESALTADA --}}
        </div>
    </div>
</div>

<style>
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    /* Enfoque dinámico de los campos */
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem {{ $temaColor }}25 !important;
        border-color: {{ $temaColor }} !important;
    }
    
    /* Botón Cancelar (Inversión Negro) */
    .btn-invert {
        background-color: #ffffff !important;
        border: 2px solid #343a40 !important;
        color: #343a40 !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        transition: all 0.3s ease;
    }
    .btn-invert:hover {
        background-color: #343a40 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
    }

    /* Botón Guardar (Inversión Dinámica) */
    .btn-tema-invert {
        background-color: #ffffff !important;
        border: 2px solid {{ $temaColor }} !important;
        color: {{ $temaColor }} !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        transition: all 0.3s ease;
    }
    .btn-tema-invert:hover {
        background-color: {{ $temaColor }} !important;
        color: #ffffff !important;
        transform: translateY(-2px);
    }

    .btn:hover i { color: #ffffff !important; }
    .invalid-feedback { font-size: 85%; }
</style>