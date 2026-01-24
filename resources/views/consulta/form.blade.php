<div class="row padding-1 p-1">
    {{-- Campos Ocultos Necesarios --}}
    <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
    <input type="hidden" name="medico_id" value="{{ $cita->medico_id }}">
    <input type="hidden" name="cita_id" value="{{ $cita->id }}">

    <div class="col-md-12 mb-3">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-medical"></i> Datos de la Atención</h3>
            </div>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="fas fa-user"></i> Paciente:</strong> 
                        <p class="text-muted">{{ $cita->paciente->usuario->nombre }} {{ $cita->paciente->usuario->apellido }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-notes-medical"></i> Motivo inicial:</strong>
                        <p class="text-muted">{{ $cita->motivo }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-user-md"></i> Médico:</strong>
                        <p class="text-muted">{{ $cita->medico->usuario->nombre }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="row">
            {{-- Alergias --}}
            <div class="col-md-6">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title text-danger"><i class="fas fa-exclamation-triangle"></i> Alergias Registradas</h3>
                    </div>
                    <div class="card-body p-2">
                        @forelse($alergias as $alergia)
                            <span class="badge badge-danger" style="font-size: 0.9rem;">{{ $alergia->nombre }}</span>
                        @empty
                            <span class="text-muted">Sin alergias reportadas.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Enfermedades --}}
            <div class="col-md-6">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title text-warning"><i class="fas fa-pills"></i> Antecedentes / Enfermedades</h3>
                    </div>
                    <div class="card-body p-2">
                        @forelse($enfermedades as $enf)
                            <span class="badge badge-warning" style="font-size: 0.9rem;">{{ $enf->nombre }}</span>
                        @empty
                            <span class="text-muted">Sin enfermedades crónicas registradas.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-heartbeat"></i> Signos Vitales</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Peso (kg)</label>
                    <input type="number" step="0.01" name="peso" class="form-control" placeholder="0.00" value="{{ old('peso', $consulta->peso ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Estatura (m)</label>
                    <input type="number" step="0.01" name="estatura" class="form-control" placeholder="0.00" value="{{ old('estatura', $consulta->estatura ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Presión Arterial</label>
                    <input type="text" name="presion_arterial" class="form-control" placeholder="120/80" value="{{ old('presion_arterial', $consulta->presion_arterial ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Temperatura (°C)</label>
                    <input type="number" step="0.1" name="temperatura" class="form-control" placeholder="36.5" value="{{ old('temperatura', $consulta->temperatura ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Frecuencia Cardíaca (bpm)</label>
                    <input type="number" name="frecuencia_cardiaca" class="form-control" placeholder="80" value="{{ old('frecuencia_cardiaca', $consulta->frecuencia_cardiaca ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-stethoscope"></i> Evaluación y Receta</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Síntomas observados</label>
                    <textarea name="sintomas" class="form-control" rows="2" placeholder="Describa...">{{ old('sintomas', $consulta->sintomas ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Diagnóstico Médico</label>
                    <textarea name="diagnostico" class="form-control" rows="2" >{{ old('diagnostico', $consulta->diagnostico ?? '') }} </textarea>
                </div>
                <div class="form-group">
                    <label>Prescripción (Receta)</label>
                    <textarea name="prescripcion" class="form-control" rows="4" placeholder="Medicamentos, dosis y duración...">{{ old('prescripcion', $consulta->prescripcion ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Observaciones adicionales</label>
                    <textarea name="observaciones" class="form-control" rows="2"> {{ old('observaciones', $consulta->observaciones ?? '') }} </textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 text-right mt-3">
        <a href="{{ route('cita.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary size-lg shadow"><i class="fas fa-save"></i> Guardar Consulta y Finalizar</button>
    </div>
</div>