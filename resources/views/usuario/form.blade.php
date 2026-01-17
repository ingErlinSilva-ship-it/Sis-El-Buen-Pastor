<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $usuario?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="apellido" class="form-label">{{ __('Apellido') }}</label>
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $usuario?->apellido) }}" id="apellido" placeholder="Apellido">
            {!! $errors->first('apellido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="celular" class="form-label">{{ __('Celular') }}</label>
            <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror" value="{{ old('celular', $usuario?->celular) }}"
            maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" id="celular" placeholder="Ej: 8888-8888">
            {!! $errors->first('celular', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="foto" class="form-label">{{ __('Foto') }}</label>
            <input type="text" name="foto" class="form-control @error('foto') is-invalid @enderror" value="{{ old('foto', $usuario?->foto) }}" id="foto" placeholder="Foto">
            {!! $errors->first('foto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        

        {{-- Div para la contraseña --}}
        <div class="form-group mb-2 mb20">
            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
            
            {{-- Usamos input-group para agrupar el campo y el botón --}}
            <div class="input-group">
                {{-- CRUCIAL: Solo cargamos el valor si proviene de un fallo de validación (old()), nunca desde la BD. --}}
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña" value="{{ old('password') }}">
                
                {{-- Botón con el icono de "Ojo" --}}
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    
                    {{-- Icono Font Awesome: fa-eye es el ojo abierto, fa-eye-slash es el ojo tachado --}}
                    <i class="fa fa-eye" id="eyeIcon"></i>
                </button>
                
                {{-- Aquí se muestra el mensaje de error de validación --}}
                {!! $errors->first('password', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
            </div>
        </div>


        {{-- Div para la selección de estado --}}
        <div class="form-group mb-2 mb20">
            <label class="form-label">{{ __('Estado de la Cuenta') }}</label>
            <div class="d-flex flex-wrap">
                
                {{-- Opción Activo (1) --}}
                <div class="form-check mb-2" style="margin-right: 20px;">
                    <input class="form-check-input" type="radio" name="estado" id="estado_activo" value="1" 
                    
                    {{-- Verifica si el valor es 1 (usado para la creación y edición) --}}
                    {{ old('estado', $usuario->estado) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label btn btn-success text-white" for="estado_activo" style="padding: 5px 10px;">
                    {{ __('Activo') }}</label>
                </div>
                
                {{-- Opción Inactivo (0) --}}
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="estado" id="estado_inactivo" value="0" 
                    
                    {{-- Verifica si el valor es 0 (usado para la creación y edición) --}}
                    {{ old('estado', $usuario->estado) == 0 ? 'checked' : '' }}>
                    <label class="form-check-label btn btn-danger text-white" for="estado_inactivo" style="padding: 5px 10px;">
                    {{ __('Inactivo') }}</label>
                </div>
            </div>
            
            {!! $errors->first('estado', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
        </div>


        {{--Div de la selección del rol a asignar--}}
        <div class="form-group mb-2 mb20">
            <label for="rol_id" class="form-label">{{ __('Rol de Acceso') }}</label>
            <select name="rol_id" id="rol_id" class="form-control @error('rol_id') is-invalid @enderror">
                <option value="">Seleccione un Rol</option>
                
                    {{-- Itera sobre la colección de roles pasada desde el controlador --}}
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" 
                
                    {{-- Marca la opción si es el valor antiguo (old) o el valor del usuario actual --}}
                    {{ old('rol_id', $usuario?->rol_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->nombre }} {{-- Muestra el nombre real del rol --}}
                </option>
                @endforeach
            </select>
            
            {!! $errors->first('rol_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>


{{-- ======================================================= --}}
{{-- Bloque de Scripts para la funcionalidad de la "Contraseña" --}}
{{-- ======================================================= --}}

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon'); 

        // Verificamos que los elementos existan antes de añadir el listener
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function (e) {
                // Previene que el botón haga submit al formulario
                e.preventDefault(); 
                
                // Determina el estado actual del input
                const isPassword = passwordInput.getAttribute('type') === 'password';
                const newType = isPassword ? 'text' : 'password';
                
                // 1. Alterna el tipo del input
                passwordInput.setAttribute('type', newType);
                
                // 2. Cambia el icono visualmente (fa-eye <-> fa-eye-slash)
                if (isPassword) {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash'); // Muestra el ojo tachado
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye'); // Muestra el ojo abierto
                }
            });
        }
    });
</script>
@endpush