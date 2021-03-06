<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('authApi', ['except' => ['login', 'register']]);
        $this->middleware('admin')->only('permissions');
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
     * Salva um novo usuário no banco
     *
     * @OA\Post (
     *     path="/auth/register",
     *     summary="AuthController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="400", description="Resposta de erro"),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar usuário",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="String", example="admin"),
     *              @OA\Property(property="email", type="String", example="admin@email.com"),
     *              @OA\Property(property="password", type="String", example="password")
     *          )
     *     ),
     *
     *     tags={"Controllers - Autenticação"},
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $name = $request->get('name');
        $email = $request->get('email');
        $pass = $request->get('password');

        if (is_null($name) || is_null($email) || is_null($pass)) {
            return response()->json([
                'status' => false,
                'message'   => 'Não foi possível realizar a operação!'
            ])->setStatusCode(400);
        }
        if (User::whereEmail($email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Este email já está cadastrado em nosso sistema!'
            ])->setStatusCode(400);
        }

        User::create([
            'name'       => $name,
            'email'      => $email,
            'password'   => Hash::make($pass),
            'profile_id' => 3, // perfil de usuário do sistema
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Operação realizada com sucesso!'
        ])->setStatusCode(201);

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

    /**
     * Retorna os usuários e suas permissões
     *
     * @OA\Post  (
     *     path="/auth/permissions",
     *     summary="AuthController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     security={{ "apiAuth": {} }},
     *
     *     tags={"Controllers - Autenticação"}
     *    )
     * @return JsonResponse
     */
    public function permissions(): JsonResponse
    {

        $res = DB::table('profile_permission')
            ->selectRaw('users.name as user, profiles.name as profile, permissions.value as level_permission')
            ->join('profiles', 'profile_permission.profile_id', '=', 'profiles.id')
            ->join('permissions', 'profile_permission.permission_id', '=', 'permissions.id')
            ->join('users', 'profiles.id', '=', 'users.profile_id')
            ->get();

        return response()->json($res)->setStatusCode(200);
    }
}
