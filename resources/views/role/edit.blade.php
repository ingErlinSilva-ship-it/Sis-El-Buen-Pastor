@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('role.update', $role->id) }}" role="form" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
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
<script>
    $(document).ready(function() {
        // Lógica adicional para edición aquí
    });
</script>
@endpush

@push('css')
<style type="text/css">
    /* Estilos específicos si fueran necesarios */
</style>
@endpush