<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiJwt
{
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
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
                return response()->json(['status' => false, 'message' => 'Token inválido!'])->setStatusCode(401);
            }
            else if ($exception instanceof TokenExpiredException){
                return response()->json(['status' => false, 'message' => 'Token expirado!'])->setStatusCode(401);
            }
            else{
                return response()->json(['status' => false, 'message' => 'Token não encontrado!'])->setStatusCode(401);
            }
        }
        return $next($request);
    }
}
