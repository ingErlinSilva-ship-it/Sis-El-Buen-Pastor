@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')

 <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-primary shadow">
                    <div class="card-header bg-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title text-primary">
                                <i class="fas fa-history"></i> {{ __('Historial de Consultas Médicas') }}
                            </h3>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead style="background-color: #6ea3f3d5; color: #444; border-bottom: 2px solid #3c8dbc;">
                                    <tr>
                                        <th>No</th>
                                        <th>Paciente</th>
                                        <th>Médico</th>
                                        <th>Diagnóstico</th>
                                        <th>Signos Vitales (P/T/PA)</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($consultas as $consulta)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            {{-- Mostramos nombres en lugar de IDs --}}
                                            <td>
                                                <strong>{{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</strong>
                                            </td>
                                            <td>Dr. {{ $consulta->medico->usuario->nombre }}</td>
                                            <td>
                                                <span class="text-truncate" style="max-width: 150px; display: inline-block;">
                                                    {{ $consulta->diagnostico }}
                                                </span>
                                            </td>
                                            {{-- Resumen de signos vitales para no saturar la tabla --}}
                                            <td class="align-middle py-2">
                                                <div class="small">
                                                    <b>Peso:</b> {{ $consulta->peso ?? 'N/A' }}kg <br>
                                                    <b>Temp:</b> {{ $consulta->temperatura ?? 'N/A' }}°C <br>
                                                    <b>P.A:</b> {{ $consulta->presion_arterial ?? 'N/A' }}
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <form action="{{ route('consulta.destroy', $consulta->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('consulta.show', $consulta->id) }}" title="Ver Receta">
                                                        <i class="fa fa-fw fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-outline-success" href="{{ route('consulta.edit', $consulta->id) }}" title="Editar">
                                                        <i class="fa fa-fw fa-edit"></i>
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este registro histórico?')">
                                                        <i class="fa fa-fw fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                </div>
            </div>
        </div>
    </div>

@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', '© 2025 - Sistema web con asistente virtual para gestión de consultas médicas. Desarrollado por Levi Ruiz y Erlin Silva.') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script>

    $(document).ready(function() {
        // Add your common script logic here...
    });

</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    /*
    {{-- You can add AdminLTE customizations here --}}
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush
