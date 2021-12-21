<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        }
        catch (\Exception $exception) {
            if ($exception instanceof TokenInvalidException){
                return response()->json(['status' => false, 'message' => 'Token inválido!']);
            }
            else if ($exception instanceof TokenExpiredException){
                return response()->json(['status' => false, 'message' => 'Token expirado!']);
            }
            else{
                return response()->json(['status' => false, 'message' => 'Token não encontrado!']);
            }
        }
        return $next($request);
    }
}
