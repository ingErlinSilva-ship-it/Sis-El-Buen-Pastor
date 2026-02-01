@extends('adminlte::page')

@section('title')
{{ config('adminlte.title') }}
@stop

@section('content_header')
@stop

@section('content')

<div class="container-fluid">
    <form method="POST" action="{{ route('role.store') }}" role="form" enctype="multipart/form-data">
        @csrf

        @include('role.form')

    </form>
</div>
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
                    title: '¡Completado!',
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
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>
@endpush