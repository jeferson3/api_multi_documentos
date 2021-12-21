<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminPermission
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

        if ($permission === 9) {
            return $next($request);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'Você não tem permissão para acessar essa área!'
        ])->setStatusCode(401);
    }
}
