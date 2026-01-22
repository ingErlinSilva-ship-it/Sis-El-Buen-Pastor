@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- 1. Limpiamos el encabezado superior para evitar duplicados visuales --}}
@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        {{-- Eliminamos la card-default y dejamos que el diseño recaiga en usuario.form --}}
        <form method="POST" action="{{ route('usuario.update', $usuario->id) }}" role="form" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            @csrf

            {{-- Incluimos el formulario dinámico --}}
            @include('usuario.form')

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
        // Lógica para previsualización de imagen actual si es necesario
    });
</script>
@endpush

@push('css')
<style type="text/css">
    /* Estilos específicos para edición si fueran necesarios */
</style>
@endpush