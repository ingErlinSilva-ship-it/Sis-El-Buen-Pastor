@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
@stop

@section('content_header')
@stop

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 show-mode">
            @include('especialidade.form')
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $(document).ready(function() {
        $('.show-mode input, .show-mode textarea').attr('readonly', true);
        $('.show-mode .card-title').text('{{ __("Visualización de Especialidad") }}');

        // Botón Regresar
        var btnRegresar = $('.show-mode .btn-invert');
        btnRegresar.attr('href', "{{ route('especialidade.index') }}");
        btnRegresar.html('<i class="fas fa-arrow-left mr-2"></i> {{ __("Regresar") }}');

        // Botón Editar
        var btnEditar = $('.show-mode .btn-success-invert, .show-mode .btn-primary-invert');
        btnEditar.attr('onclick', "window.location.href='{{ route('especialidade.edit', $especialidade->id) }}'");
        btnEditar.attr('type', 'button');
        btnEditar.html('<i class="fas fa-edit mr-2"></i> {{ __("Editar Especialidad") }}');

        // Clase específica para la nomenclatura morada
        btnEditar.addClass('btn-purple-invert').removeClass('btn-success-invert btn-primary-invert');
    });
</script>
@endpush

@push('css')
<style>
    .btn-invert { 
        background-color: #ffffff !important;
        border: 2px solid #343a40 !important; 
        color: #343a40 !important; 
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
    }

    .btn-invert i {
        color: #343a40 !important;
    }

    .btn-invert:hover { 
        background-color: #343a40 !important; 
        color: #ffffff !important; 
    }

    .btn-invert:hover i {
        color: #ffffff !important;
    }

    .btn-purple-invert { 
        background-color: #ffffff !important;
        border: 2px solid #8e44ad !important; 
        color: #8e44ad !important; 
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease-in-out !important;
    }

    .btn-purple-invert i {
        color: #8e44ad !important;
    }

    .btn-purple-invert:hover { 
        background-color: #8e44ad !important; 
        color: #ffffff !important; 
    }

    .btn-purple-invert:hover i {
        color: #ffffff !important;
    }

    .show-mode input, .show-mode textarea {
        background-color: #fcfaff !important;
        border-color: #e9ecef !important;
        pointer-events: none; 
    }
    
    .show-mode .card-header { 
        border-top: 5px solid #8e44ad !important; 
    }

    .show-mode .rounded-circle { 
        background-color: #f3e5f5 !important; 
    }
    
    /* Iconos internos del formulario en morado */
    .show-mode .fa-stethoscope, .show-mode .fa-tag, .show-mode .fa-font ,.show-mode .fa-microscope{
        color: #8e44ad !important;
    }

    .btn:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush