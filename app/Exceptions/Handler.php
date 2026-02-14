<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException; // IMPORTANTE
use Illuminate\Support\Facades\Redirect; // IMPORTANTE

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // 1. CAPTURA DE ERRORES DE DUPLICADOS (SQLSTATE 23000)
        $this->renderable(function (QueryException $e, $request) {
            // El código 23000 detecta duplicados en cualquier tabla
            if ($e->getCode() === '23000') {
                return Redirect::back()
                    ->withInput()
                    ->withErrors(['nombre' => 'Este nombre ya está registrado en el catálogo.']) // Esto activa el @error('nombre')
                    ->with('error', '¡Atención! El nombre que intentas usar ya existe en el sistema.');
            }
        });

        // 2. TU LÓGICA DE PERMISOS (Se mantiene igual)
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'No tienes permisos.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error_toast', 'No tienes permisos para acceder a esa sección.');
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}