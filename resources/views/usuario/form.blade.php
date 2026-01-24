@php
    $esEdicion = isset($usuario->id);
    $temaColor = $esEdicion ? '#28a745' : '#007bff';
    $temaFondoIcono = $esEdicion ? '#e8f5e9' : '#e7f1ff';
    $sombraFocus = $esEdicion ? 'rgba(40, 167, 69, 0.15)' : 'rgba(0, 123, 255, 0.15)';
@endphp

<div class="container-fluid pt-4">
    <div class="row mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                
                {{-- Header Dinámico --}}
<div class="card-header bg-white border-bottom py-3 px-4" 
     style="border-top: 4px solid {{ $temaColor }}; border-radius: 12px 12px 0 0;">
    <div class="d-flex align-items-center">
        <div class="rounded-circle p-2 mr-3" style="background-color: {{ $temaFondoIcono }};">
            <i class="fas fa-user-check {{ $esEdicion ? 'text-success' : 'text-primary' }}"></i>
        </div>
        <div>
            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.2rem;">
                {{ $esEdicion ? __('Actualizar Datos del Usuario') : __('Registro de Nuevo Usuario') }}
            </h3>
        </div>
    </div>
</div>
                
                <div class="card-body p-4">
                    <div class="row">
                        {{-- Sección de Foto con Previsualización --}}
                        <div class="col-md-4 text-center border-right pr-4">
                            <label class="text-dark font-weight-bold mb-3 d-block"><i class="fas fa-camera mr-1 text-muted"></i> Foto de Perfil</label>
                            <div class="position-relative d-inline-block">
                                <img id="preview" 
                                     src="{{ $usuario->foto ? asset('storage/'.$usuario->foto) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" 
                                     class="rounded-circle shadow-sm border" 
                                     style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff !important;">
                                <label for="foto" class="btn btn-sm btn-primary position-absolute shadow" 
                                       style="bottom: 5px; right: 5px; border-radius: 50%; width: 35px; height: 35px; padding: 6px;">
                                    <i class="fas fa-pencil-alt"></i>
                                </label>
                            </div>
                            <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                            <small class="text-muted d-block mt-2">Formatos permitidos: JPG, PNG. Máx 2MB.</small>
                            @error('foto') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Datos Personales --}}
                        <div class="col-md-8 pl-4">
                            <div class="row">
                                {{-- Nombre --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="nombre" class="font-weight-bold small text-muted">NOMBRE</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $usuario?->nombre) }}" required placeholder="Juan Carlos">
                                </div>
                                {{-- Apellido --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="apellido" class="font-weight-bold small text-muted">APELLIDO</label>
                                    <input type="text" name="apellido" id="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $usuario?->apellido) }}" required placeholder="Pérez Reyes">
                                </div>
                                {{-- Email --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="email" class="font-weight-bold small text-muted">EMAIL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope {{ $esEdicion ? 'text-success' : 'text-primary' }}"></i></span>
                                        </div>
                                        <input type="email" name="email" id="email" class="form-control border-left-0 @error('email') is-invalid @enderror" value="{{ old('email', $usuario?->email) }}" required placeholder="ejemplo@pastor.com">
                                    </div>
                                </div>
                                {{-- Celular --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="celular" class="font-weight-bold small text-muted">CELULAR</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-mobile-alt {{ $esEdicion ? 'text-success' : 'text-primary' }}"></i></span>
                                        </div>
                                        <input type="text" name="celular" id="celular" class="form-control border-left-0 @error('celular') is-invalid @enderror" value="{{ old('celular', $usuario?->celular) }}" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="88888888">
                                    </div>
                                </div>
                                {{-- Rol --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="rol_id" class="font-weight-bold small text-muted">ROL DE ACCESO</label>
                                    <select name="rol_id" id="rol_id" class="form-control select2 @error('rol_id') is-invalid @enderror">
                                        <option value="">Seleccione un Rol</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('rol_id', $usuario?->rol_id) == $role->id ? 'selected' : '' }}>{{ $role->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Password --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="password" class="font-weight-bold small text-muted">CONTRASEÑA</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control border-right-0 @error('password') is-invalid @enderror" placeholder="{{ $esEdicion ? 'Dejar en blanco para no cambiar' : 'Mín. 8 caracteres' }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary border-left-0 bg-white" type="button" id="togglePassword">
                                                <i class="fa fa-eye" id="eyeIcon text-muted"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Estado --}}
                                <div class="col-12 mt-2">
                                    <label class="font-weight-bold small text-muted d-block">ESTADO DE CUENTA</label>
                                    <div class="btn-group btn-group-toggle shadow-sm" data-toggle="buttons">
                                        <label class="btn btn-outline-success border-right-0 {{ old('estado', $usuario->estado) == 1 ? 'active' : '' }}" style="border-radius: 8px 0 0 8px;">
                                            <input type="radio" name="estado" value="1" {{ old('estado', $usuario->estado) == 1 ? 'checked' : '' }}> <i class="fas fa-check-circle mr-1"></i> Activo
                                        </label>
                                        <label class="btn btn-outline-danger {{ old('estado', $usuario->estado) == 0 ? 'active' : '' }}" style="border-radius: 0 8px 8px 0;">
                                            <input type="radio" name="estado" value="0" {{ old('estado', $usuario->estado) == 0 ? 'checked' : '' }}> <i class="fas fa-times-circle mr-1"></i> Inactivo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer bg-light border-top d-flex justify-content-end py-3 px-4" style="border-radius: 0 0 12px 12px;">
                    <a href="{{ route('usuario.index') }}" class="btn btn-outline-secondary mr-3 px-4 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-times-circle mr-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn {{ $esEdicion ? 'btn-success' : 'btn-primary' }} px-5 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                        <i class="fas {{ $esEdicion ? 'fa-sync-alt' : 'fa-save' }} mr-2"></i> {{ $esEdicion ? 'Actualizar Usuario' : 'Guardar Usuario' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    // 1. Script para el Ojo de la Contraseña
    document.getElementById('togglePassword')?.addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        icon.classList.toggle('fa-eye-slash');
    });

    // 2. Previsualización de Foto en tiempo real
    document.getElementById('foto').onchange = evt => {
        const [file] = document.getElementById('foto').files;
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush

@push('css')
<style>
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem {{ $sombraFocus }} !important;
        border-color: {{ $temaColor }} !important;
    }
    .btn-group-toggle .btn.active {
        box-shadow: none !important;
        background-color: {{ $temaColor == '#28a745' ? '#28a745' : '#007bff' }};
        color: #fff !important;
    }
</style>
@endpush