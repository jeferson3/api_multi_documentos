<?php

namespace App\Http\Middleware;

use App\Models\Permission\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerentePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $permission = auth()->user()->getPermission();

        // verifica se o usuário tem o nível de acesso de admin ou gerente
        if ($permission === 5 || $permission === 9) {
            return $next($request);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'Você não tem permissão para acessar essa área!'
        ])->setStatusCode(401);
    }
}
