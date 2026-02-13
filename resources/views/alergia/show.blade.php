@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-11 show-mode">
            @include('alergia.form')
        </div>
    </div>
</div>
@stop

@push('js')
    <script>
        $(document).ready(function () {
            $('.show-mode input, .show-mode textarea').attr('readonly', true);
            $('.show-mode .card-title').text('{{ __("Visualización de Alergia") }}');

            var btnRegresar = $('.show-mode .btn-invert');
            btnRegresar.attr('href', "{{ route('alergia.index') }}");
            btnRegresar.html('<i class="fas fa-arrow-left mr-2"></i> {{ __("Regresar") }}');

            var btnEditar = $('.show-mode .btn-primary-invert, .show-mode .btn-success-invert');

            @if(Auth::user()->rol_id == 1)
                btnEditar.attr('onclick', "window.location.href='{{ route('alergia.edit', $alergia->id) }}'");
                btnEditar.attr('type', 'button');
                btnEditar.html('<i class="fas fa-edit mr-2"></i> {{ __("Editar Registro") }}');
                btnEditar.addClass('btn-purple-invert').removeClass('btn-primary-invert btn-success-invert');
                @else
                // SI ES DOCTOR: Ocultamos el botón de acción por completo
                btnEditar.hide();
            @endif
        });
    </script>
@endpush

@push('css')
    <style type="text/css">
        .show-mode .card-header {
            border-top: 5px solid #8e44ad !important;
        }

        .show-mode .rounded-circle {
            background-color: #f3e5f5 !important;
        }

        .show-mode .text-primary,
        .show-mode .text-success,
        .show-mode .fa-allergies,
        .show-mode .fa-tag,
        .show-mode .label-custom {
            color: #8e44ad !important;
        }

        .show-mode input,
        .show-mode textarea {
            background-color: #fcfaff !important;
            border-color: #e9ecef !important;
            pointer-events: none;
        }

        .btn.btn-purple-invert,
        a.btn-purple-invert {
            background-color: #ffffff !important;
            border: 2px solid #8e44ad !important;
            color: #8e44ad !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease-in-out !important;
            display: inline-flex !important;
            align-items: center;
            text-decoration: none !important;
        }

        .btn-purple-invert i {
            color: #8e44ad !important;
        }

        .btn-purple-invert.btn-purple-invert:hover {
            background-color: #8e44ad !important;
            color: #ffffff !important;
            border-color: #8e44ad !important;
        }

        .btn-purple-invert.btn-purple-invert:hover i {
            color: #ffffff !important;
        }

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

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(142, 68, 173, 0.25) !important;
        }
    </style>
@endpush