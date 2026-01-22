<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
        if ($request->is('api/*')) {
            return response()->json(['message' => 'No tienes permisos.'], 403);
        }

        // Si intenta entrar a una ruta bloqueada, lo mandamos al dashboard con un mensaje
        return redirect()->route('dashboard')
            ->with('error_toast', 'No tienes permisos para acceder a esa sección.');
    });
    }
}
