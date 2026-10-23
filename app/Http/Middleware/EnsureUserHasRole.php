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

        if (! $user || ! $user->ativo || ! $user->estaAprovado()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Gestores (presidente/vice/diretor/admin) tem acesso a tudo.
        if ($user->isGestor()) {
            return $next($request);
        }

        if (! empty($roles) && ! in_array($user->role, $roles, true)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
