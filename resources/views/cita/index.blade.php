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
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Citas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cita.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Crear Nueva Cita') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
									<th >Paciente</th>
									<th >Médico</th>
									<th >Fecha</th>
									<th >Hora</th>
									<th >Duración</th>
									<th >Motivo</th>
									<th >Estado</th>
									<th >Origen</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citas as $cita)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $cita->paciente->usuario->nombre }} {{ $cita->paciente->usuario->apellido }}</td>
										<td >{{ $cita->medico->usuario->nombre }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</td>
										<td >{{ $cita->duracion_minutos }}</td>
										<td >{{ $cita->motivo }}</td>
										<td>@if($cita->estado == 'pendiente')
                                            <span class="badge badge-warning text-dark" style="font-size: 0.9em; padding: 5px 10px;">
                                                <i class="fas fa-clock"></i> Pendiente
                                            </span>
                                        @elseif($cita->estado == 'confirmada')
                                            <span class="badge badge-success" style="font-size: 0.9em; padding: 5px 10px;">
                                                <i class="fas fa-check-circle"></i> Confirmada
                                            </span>
                                        @elseif($cita->estado == 'cancelada')
                                            <span class="badge badge-danger" style="font-size: 0.9em; padding: 5px 10px;">
                                                <i class="fas fa-times-circle"></i> Cancelada
                                            </span>
                                        @elseif($cita->estado == 'asistida')
                                            <span class="badge badge-danger" style="font-size: 0.9em; padding: 5px 10px;">
                                                <i class="fas fa-check-circle"></i> Finalizada
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">{{ $cita->estado }}</span>
                                        @endif
                                    </td>
										<td >{{ $cita->origen }}</td>
										<td >{{ $cita->chat_session_id }}</td>
										<td >{{ $cita->token_confirmacion }}</td>

                                            <td>
                                                <form action="{{ route('cita.destroy', $cita->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cita.show', $cita->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cita.edit', $cita->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Deseas Eliminar esta Cita?') ? 
                                                    this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                    <a class="btn btn-sm" href="{{ route('consultas.atender', $cita->id) }}" style="background-color: #138fa7; border-color: #138fa7; color: white;">
                                                        <i class="fa fa-fw fa-stethoscope"></i> Atender
                                                    </a>
                                                    </a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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

