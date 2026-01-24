<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed')) != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>

        {{-- Nombre del usuario en la parte inferior --}}
        {{-- Contenedor con ajuste para eliminar scroll --}}
        <div class="user-panel-bottom px-2 pb-4" style="position: absolute; bottom: 0; width: 100%; left: 0; right: 0; overflow: hidden !important;">
            
            {{-- Card de Usuario --}}
            <div class="mx-2 p-3 shadow-sm user-info-card" style="background-color: #0b4c81; border-radius: 1rem;">
                <div class="info">
                    <p class="text-uppercase font-weight-bold mb-1" style="font-size: 0.65rem; color: #94a3b8; letter-spacing: 0.05em;">Usuario</p>
                    <p class="font-weight-bold text-white text-truncate mb-1">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</p>
                    <p class="mt-1 mb-0" style="font-size: 0.9rem; color: #38bdf8; text-transform: capitalize;">
                        @php 
                            $user = Auth::user();
                                // Usamos el ayudante optional() o el operador ??
                                // Esto evita que el fantasmita aparezca si el rol no existe
                                $rolNombreRaw = optional($user->role)->nombre ?? 'Sin Rol';
                                $rolNombre = strtolower($rolNombreRaw);
                        @endphp

                        @if(str_contains($rolNombre, 'admin')) 
                                Administrador
                            @elseif(str_contains($rolNombre, 'doctor') || str_contains($rolNombre, 'medic')) 
                                Doctor
                            @elseif(str_contains($rolNombre, 'paciente')) 
                                Paciente
                            @else 
                                {{ $userRole->nombre ?? 'Sin Rol' }}
                            @endif
                    </p>
                </div>
            </div>

            {{-- Separador sutil --}}
            <div class="mx-4 my-2" style="border-top: 1px solid rgba(255,255,255,0.1);"></div>

            {{-- Botón de salida --}}
            <div class="px-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm btn-block d-flex align-items-center justify-content-center" ...>
                        <i class="fas fa-sign-out-alt"></i> 
                        <span class="ml-2 btn-text">Cerrar Sesión</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
        <style>
        /* 1. Por defecto, ocultamos el texto si el sidebar está colapsado */
        .sidebar-collapse .user-panel-bottom .user-info-card,
        .sidebar-collapse .user-panel-bottom .btn-text,
        .sidebar-collapse .user-panel-bottom hr,
        .sidebar-collapse .user-panel-bottom p {
            display: none !important;
        }

        /* 2. LA MÁGICA: Si el usuario pasa el mouse (hover), VOLVEMOS a mostrar todo */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom .user-info-card,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom .btn-text,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom hr,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom br,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom p {
            display: block !important;
        }

        /* Ajuste del botón para que no se vea gigante al expandir en hover */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom .btn {
            width: 100% !important;
        }

        /* Cuando está totalmente colapsado y NO hay mouse encima, el botón se centra */
        .sidebar-collapse .user-panel-bottom .btn {
            width: 40px;
            margin-left: auto;
            margin-right: auto;
        }

        .user-panel-bottom {
            transition: all 0.3s ease;
        }
    </style>
</aside>
