<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Garante que o usuario logado possua um dos papeis informados.
     * Uso nas rotas: ->middleware('role:admin') ou 'role:admin,professor'.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        // Admin tem acesso a tudo.
        if ($user->isAdmin()) {
            return $next($request);
        }

        if (! empty($roles) && ! in_array($user->role, $roles, true)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
