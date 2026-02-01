@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <form method="POST" action="{{ route('medico.update', $medico->id) }}" role="form"
                enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header bg-white py-3 shadow-sm" style="border-top: 5px solid #28a745;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 mr-3"
                                style="background-color: #e8f5e9; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-edit text-success"></i>
                            </div>
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                                {{ __('Actualizar Expediente del Médico') }}
                            </h3>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Panel Lateral --}}
                            <div class="col-md-4 text-center border-right">
                                <div class="mb-3 d-flex justify-content-center">
                                    @if($medico->usuario && $medico->usuario->foto)
                                        <div class="rounded-circle shadow-sm"
                                            style="width: 140px; height: 140px; overflow: hidden; border: 5px solid #28a745;">
                                            <img src="{{ asset('storage/' . $medico->usuario->foto) }}" alt="Foto"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                            style="width: 140px; height: 140px; border: 2px dashed #28a745;">
                                            <i class="fas fa-user-md text-success" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- 1er Nombre y 1er Apellido --}}
                                <h4 class="font-weight-bold text-dark mb-1 text-capitalize">
                                    {{ explode(' ', $medico->usuario->nombre)[0] }}
                                    {{ explode(' ', $medico->usuario->apellido)[0] }}
                                </h4>

                                {{-- Correo Electrónico --}}
                                <p class="text-muted small mb-3">{{ $medico->usuario->email }}</p>

                                {{-- Rol del Usuario --}}
                                <span class="badge-role-edit shadow-sm">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    {{ optional($medico->usuario->role)->nombre ?? 'Médico' }}
                                </span>
                            </div>

                            {{-- Columna Derecha --}}
                            <div class="col-md-8">
                                <div class="row px-3">

                                    {{-- Usuario --}}
                                    <div class="col-md-12 mb-4">
                                        <label class="label-custom text-success"><i class="fas fa-user-circle mr-1"></i>
                                            Usuario del Sistema</label>
                                        <div class="input-box-container">
                                            <select name="usuario_id"
                                                class="form-control-custom @error('usuario_id') is-invalid @enderror">
                                                @foreach ($usuarios as $id => $nombre)
                                                    <option value="{{ $id }}" {{ old('usuario_id', $medico->usuario_id) == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Especialidad --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="label-custom text-success"><i
                                                class="fas fa-fw fa-microscope mr-1"></i> Especialidad Médica</label>
                                        <div class="input-box-container">
                                            <select name="especialidad_id"
                                                class="form-control-custom @error('especialidad_id') is-invalid @enderror">
                                                @foreach ($especialidades as $id => $nombre)
                                                    <option value="{{ $id }}" {{ old('especialidad_id', $medico->especialidad_id) == $id ? 'selected' : '' }}>{{ $nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Código MINSA --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="label-custom text-success"><i class="fas fa-id-card mr-1"></i>
                                            Código MINSA</label>
                                        <div class="input-box-container">
                                            <input type="text" name="codigo_minsa"
                                                class="form-control-custom font-weight-bold"
                                                value="{{ old('codigo_minsa', $medico->codigo_minsa) }}" maxlength="6">
                                        </div>
                                    </div>

                                    {{-- Descripción --}}
                                    <div class="col-md-12">
                                        <label class="label-custom text-success"><i class="fas fa-file-alt mr-1"></i>
                                            Descripción Profesional</label>
                                        <div class="input-box-container">
                                            <textarea name="descripcion" class="form-control-custom"
                                                rows="4">{{ old('descripcion', $medico->descripcion) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 d-flex justify-content-end py-3 px-4">
                        <a href="{{ route('medico.index') }}" class="btn btn-invert mr-3 px-4 shadow-sm">
                            <i class="fas fa-times-circle mr-2"></i> {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-success-invert px-5 shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i> {{ __('Actualizar Médico') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('css')
    <style>
        .input-box-container {
            background-color: #f8fbff;
            border-left: 5px solid #28a745 !important;
            border-radius: 8px;
        }

        .form-control-custom {
            background: transparent !important;
            border: 1px solid #e2e8f0 !important;
            border-left: none !important;
            border-radius: 0 8px 8px 0;
            padding: 12px 15px;
            height: auto;
            width: 100%;
            color: #334155;
            font-weight: 500;
        }

        .form-control-custom:focus {
            outline: none;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.1);
        }

        .label-custom {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 800;
            margin-bottom: 5px;
            display: block;
        }

        .badge-role-edit {
            background-color: #e8f5e9;
            color: #1b5e20;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid #28a745;
            display: inline-block;
        }

        .btn-invert,
        .btn-success-invert {
            background-color: #ffffff !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease;
            border-width: 2px !important;
        }

        .btn-invert {
            border: 2px solid #343a40 !important;
            color: #343a40 !important;
        }

        .btn-invert:hover {
            background-color: #343a40 !important;
            color: #fff !important;
        }

        .btn-success-invert {
            border: 2px solid #28a745 !important;
            color: #28a745 !important;
        }

        .btn-success-invert:hover {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('input[name="codigo_minsa"]').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: { popup: 'rounded-lg' }
                });
            @endif
        });
    </script>
@endpush