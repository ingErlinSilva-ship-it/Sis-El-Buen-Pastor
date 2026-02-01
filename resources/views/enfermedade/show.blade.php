@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 show-mode">
            @include('enfermedade.form')
        </div>
    </div>
</div>
@stop

@push('js')
    <script>
        $(document).ready(function () {
            // Bloqueo de campos
            $('.show-mode input, .show-mode textarea').attr('readonly', true);

            // Ajuste de textos en el card
            $('.show-mode .card-title').text('{{ __("Visualización de Enfermedad") }}');
            $('.show-mode h4').text('{{ __("Detalle de Patología") }}');

            // Botón Regresar (Mantiene su estilo negro neutro)
            var btnRegresar = $('.show-mode .btn-invert');
            btnRegresar.attr('href', "{{ route('enfermedade.index') }}");
            btnRegresar.html('<i class="fas fa-arrow-left mr-2"></i> {{ __("Regresar") }}');

            // Botón Editar (Forzamos clase Púrpura y quitamos las de éxito/primaria)
            var btnEditar = $('.show-mode .btn-primary-invert, .show-mode .btn-success-invert');
            btnEditar.attr('onclick', "window.location.href='{{ route('enfermedade.edit', $enfermedade->id) }}'");
            btnEditar.attr('type', 'button');
            btnEditar.html('<i class="fas fa-edit mr-2"></i> {{ __("Editar Registro") }}');
            btnEditar.addClass('btn-purple-invert').removeClass('btn-primary-invert btn-success-invert');
        });
    </script>
@endpush

@push('css')
    <style type="text/css">
        /* 1. IDENTIDAD MORADA SHOW */
        .show-mode .card-header {
            border-top: 5px solid #8e44ad !important;
        }

        .show-mode .rounded-circle {
            background-color: #f3e5f5 !important;
        }

        .show-mode .fa-virus,
        .show-mode .label-custom,
        .show-mode .fa-tag,
        .show-mode .fa-file-medical-alt {
            color: #8e44ad !important;
        }

        /* 2. BOTÓN MORADO - ESTRUCTURA FINAL */
        /* Usamos triple selector para asegurar que mande sobre AdminLTE */
        .btn.btn-purple-invert,
        a.btn-purple-invert,
        .show-mode .btn-purple-invert {
            background-color: #ffffff !important;
            border: 2px solid #8e44ad !important;
            color: #8e44ad !important;
            /* Texto Morado Inicial */
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease-in-out !important;
            display: inline-flex !important;
            align-items: center;
            text-decoration: none !important;
        }

        /* Icono Morado Inicial */
        .btn-purple-invert i {
            color: #8e44ad !important;
        }

        /* 3. EL HOVER (INVERSIÓN) - ESPECIFICIDAD EXTREMA */
        /* Al repetir la clase .btn-purple-invert duplicamos su fuerza en el navegador */
        .btn-purple-invert.btn-purple-invert:hover {
            background-color: #8e44ad !important;
            /* Relleno Morado */
            color: #ffffff !important;
            /* Texto Blanco */
            border-color: #8e44ad !important;
        }

        /* Forzamos el icono a blanco en hover */
        .btn-purple-invert.btn-purple-invert:hover i {
            color: #ffffff !important;
        }

        /* 4. BOTÓN REGRESAR (Negro) */
        .btn-invert {
            background-color: #ffffff !important;
            border: 2px solid #343a40 !important;
            color: #343a40 !important;
            border-radius: 8px !important;
        }

        .btn-invert:hover {
            background-color: #343a40 !important;
            color: #ffffff !important;
        }

        /* 5. BLOQUEO VISUAL */
        .show-mode input,
        .show-mode textarea {
            background-color: #fcfaff !important;
            border-color: #e9ecef !important;
            pointer-events: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(142, 68, 173, 0.3) !important;
        }
    </style>
@endpush