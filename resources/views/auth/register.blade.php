<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | El Buen Pastor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body, html { height: 100%; margin: 0; }
        .split-screen { display: flex; height: 100vh; width: 100%; }
        .image-side {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), 
                        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: none;
        }
        @media (min-width: 992px) { .image-side { display: flex; align-items: center; justify-content: flex-start; padding: 40px; } }
        .form-side { width: 100%; max-width: 600px; display: flex; align-items: center; justify-content: center; padding: 40px; background: white; position: relative; overflow-y: auto; }
        @media (min-width: 992px) { .form-side { width: 550px; } }
    </style>
</head>
<body class="bg-gray-100">

    <div class="split-screen">
        <div class="image-side">
            <div class="text-white px-12">
                <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">Consultorio Médico</h1>
                <h1 class="text-6xl font-extrabold mb-4 uppercase tracking-tight leading-none">El Buen Pastor</h1>
                <p class="text-2xl italic font-light opacity-90 border-l-4 border-white pl-4">"Nuestro compromiso está con la salud de los pacientes"</p>
            </div>
        </div>

        <div class="form-side">
            {{-- Botón Volver --}}
            <a href="/" class="absolute top-8 left-8 text-gray-400 hover:text-blue-600 transition-colors duration-300 flex items-center gap-2 font-medium">
                <i class="fas fa-chevron-left"></i>
                <span>Volver</span>
            </a>

            <div class="w-full max-w-md my-10">
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/bg/logocotCL.jpg') }}" alt="logo" class="mx-auto mb-4 shadow-sm rounded-lg" style="max-height: 80px; width: auto;">
                    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">CREAR CUENTA</h2>
                    <p class="text-gray-500 mt-2">Únase a nuestra plataforma médica</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required autofocus class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
                        </div>

                        {{-- Apellido --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
                            <x-input-error :messages="$errors->get('apellido')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Celular --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Celular</label>
                        <input type="text" name="celular" value="{{ old('celular') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)" pattern="[0-9]{8}" 
                        title="El número debe tener exactamente 8 dígitos" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50" 
                        placeholder="Ej: 88888888">
                        <x-input-error :messages="$errors->get('celular')" class="mt-1" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50" placeholder="correo@ejemplo.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Contraseña</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50" placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50" placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-blue-200 transition-all duration-300 uppercase tracking-wide">
                            Registrarse ahora
                        </button>
                    </div>

                    <p class="text-center mt-4 text-gray-600 text-sm font-medium">
                        ¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Inicia sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>