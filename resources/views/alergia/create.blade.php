@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content')
<section class="content container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="POST" action="{{ route('alergia.store') }}" role="form" enctype="multipart/form-data">
                @csrf
                @include('alergia.form')
            </form>
        </div>
    </div>
</section>
@stop

@section('footer')
<div class="float-right d-none d-sm-block text-muted">Version: {{ config('app.version', '1.0.0') }}</div>
<strong>© 2025 - Consultorio El Buen Pastor. Desarrollado por Levi Ruiz y Erlin Silva.</strong>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
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

@push('css')
    <style type="text/css">
        .content-wrapper {
            background-color: #f4f6f9 !important;
        }

        .rounded-lg {
            border-radius: 15px !important;
        }
    </style>
@endpush