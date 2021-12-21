<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('authApi', ['except' => ['login']]);
    }

    /**
     * Faz o login e retorna o token JWT
     *
     * @OA\Post  (
     *     path="/auth/login",
     *     summary="AuthController",
     *     @OA\Response(response="400", description="Resposta com erro"),
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          description="Fazer login",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="String", example="admin@email.com"),
     *              @OA\Property(property="password", type="String", example="password"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Autenticação"}
     * )
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials, true)) {
            return response()->json(['status' => false, 'message' => 'Credenciais inválidas!'], 400);
        }

        return response()->json(['token' => $token])->setStatusCode(200);
    }

    /**
     * Retorna o usuário logado
     *
     * @OA\Post  (
     *     path="/auth/me",
     *     summary="AuthController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     security={{ "apiAuth": {} }},
     *
     *     tags={"Controllers - Autenticação"}
     *    )
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Faz o logout do usuário invalidando o token
     *
     * @OA\Post  (
     *     path="/auth/logout",
     *     summary="AuthController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     security={{ "apiAuth": {} }},
     *
     *     tags={"Controllers - Autenticação"}
     *    )
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['status' => true, 'message' => 'Logout realizado com sucesso']);
    }

    /**
     * Refresh do token.
     *
     * @OA\Post  (
     *     path="/auth/refresh",
     *     summary="AuthController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     security={{ "apiAuth": {} }},
     *
     *     tags={"Controllers - Autenticação"}
     *    )
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return response()->json(['token' => auth()->refresh()])->setStatusCode(200);
    }
}
