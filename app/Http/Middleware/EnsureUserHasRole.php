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

        // O 'admin' da V1 equivale ao presidente/super — acesso total.
        if ($user->role === \App\Models\User::ROLE_ADMIN) {
            return $next($request);
        }

        // Sem papeis exigidos: basta estar logado e aprovado.
        if (empty($roles)) {
            return $next($request);
        }

        // Papeis exigidos: o usuario precisa ter EXATAMENTE um deles.
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
