<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }} d-flex flex-column">

    {{-- 1. Logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- 2. Menú (flex-fill hace que ocupe el espacio sobrante con scroll propio) --}}
    <div class="sidebar flex-fill" style="overflow-y: auto;">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu">
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

    {{-- 3. Panel de Usuario Inferior (Bloque fijo al fondo, evita sobreposición) --}}
    <div class="user-panel-bottom py-3 border-top border-secondary">
        <div class="container-fluid px-3">
            
            {{-- Card de Usuario Azul --}}
            <div class="user-info-card shadow-sm mb-3" style="background-color: #0b4c81; border-radius: 12px; overflow: hidden;">
                <div class="d-flex align-items-center p-2">
                    {{-- Foto Circular --}}
                    <div class="user-img-circle mr-2 border border-info shadow-sm" style="flex-shrink: 0;">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/'.Auth::user()->foto) }}" alt="User Image" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="fas fa-user text-xs text-white"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Texto del Usuario --}}
                    <div class="info text-truncate">
                        <small class="text-uppercase d-block font-weight-bold" style="font-size: 0.6rem; color: #94a3b8; line-height: 1;">Usuario</small>
                        
                        {{-- Muestra solo el primer nombre --}}
                        <span class="font-weight-bold text-white d-block text-truncate" style="font-size: 0.85rem;" title="{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}">
                            {{ explode(' ', trim(Auth::user()->nombre))[0] }}
                        </span>

                        <span class="badge badge-info p-0 px-1" style="font-size: 0.65rem; background: rgba(56, 189, 248, 0.2); color: #38bdf8; text-transform: capitalize;">
                             {{ optional(Auth::user()->role)->nombre ?? 'Sin Rol' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Botón Salir Responsivo --}}
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm btn-block d-flex align-items-center justify-content-center py-2 shadow-sm" style="border-radius: 8px; font-weight: bold;">
                    <i class="fas fa-sign-out-alt"></i> 
                    <span class="ml-2 btn-text">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>

    <style>
        /* Ajuste estructural para evitar que el sidebar crezca infinito */
        .main-sidebar { height: 100vh !important; }
        
        /* Efecto de transición suave */
        .user-panel-bottom {
            background: rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        /* --- MANEJO DE SIDEBAR COLAPSADO --- */

        /* 1. Ocultar elementos cuando está cerrado */
        .sidebar-collapse .user-panel-bottom .user-info-card,
        .sidebar-collapse .user-panel-bottom .btn-text {
            display: none !important;
        }

        /* 2. Botón se vuelve icono cuadrado centrado */
        .sidebar-collapse .user-panel-bottom .btn {
            width: 40px;
            height: 40px;
            margin: 0 auto;
            padding: 0;
            display: flex !important;
        }

        /* 3. Re-mostrar todo al hacer Hover (expandir) */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom .user-info-card,
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom .btn-text {
            display: block !important;
        }
        
        /* 4. Botón recupera su ancho en Hover */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .user-panel-bottom .btn {
            width: 100% !important;
            height: auto;
            padding: .5rem;
        }

        .user-img-circle {
            border-radius: 50%;
        }
    </style>
</aside>