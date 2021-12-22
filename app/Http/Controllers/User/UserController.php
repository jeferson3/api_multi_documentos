<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Retorna todos os usuários do banco
     *
     * @OA\Get (
     *     path="/users",
     *     summary="UserController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     tags={"Controllers - Usuários"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(User::all()->toArray())->setStatusCode(200);
    }

    /**
     * Salva um novo usuário no banco
     *
     * @OA\Post (
     *     path="/users",
     *     summary="UserController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="400", description="Resposta de erro"),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar usuário",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="String", example="user"),
     *              @OA\Property(property="email", type="String", example="user@email.com"),
     *              @OA\Property(property="password", type="String", example="password"),
     *              @OA\Property(property="profile_id", type="Int", example=3),
     *          )
     *     ),
     *
     *     tags={"Controllers - Usuários"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $name        = $request->get('name');
        $email       = $request->get('email');
        $pass        = $request->get('password');
        $profile_id  = $request->get('profile_id') ?? 3; // se não for passado um perfil é setado o 3 que é de usuário do sistema

        if (is_null($name) || is_null($email) || is_null($pass)) {
            return response()->json([
                'status' => false,
                'message'   => 'Não foi possível realizar a operação!'
            ])->setStatusCode(400);
        }

        if (User::whereEmail($email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Este email já está cadastrado no nosso sistema!'
            ])->setStatusCode(400);
        }

        User::create([
            'name'       => $name,
            'email'      => $email,
            'password'   => Hash::make($pass),
            'profile_id' => $profile_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Operação realizada com sucesso!'
        ])->setStatusCode(201);
    }

    /**
     * Retorna um usuário específico pelo ID
     *
     * @OA\Get (
     *     path="/users/{id}",
     *     summary="UserController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do usuário",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     tags={"Controllers - Usuários"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if (!$user = User::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        return response()->json($user)->setStatusCode(200);
    }

    /**
     * Atualiza um usuário no banco
     *
     *  @OA\Put (
     *     path="/users/{id}",
     *     summary="UserController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do usuário",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar usuário",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="String", example="user"),
     *              @OA\Property(property="email", type="String", example="user@email.com"),
     *              @OA\Property(property="password", type="String", example="password"),
     *              @OA\Property(property="profile_id", type="Int", example=3),
     *          )
     *     ),
     *
     *     tags={"Controllers - Usuários"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @param  Request  $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {

        if (!$user = User::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $name        = $request->get('name');
        $email       = $request->get('email');
        $pass        = $request->get('password');

        if (User::where('email', $email)->exists() && $user->email != $email) {
            return response()->json([
                'status' => false,
                'message' => 'Este email já está cadastrado no nosso sistema!'
            ])->setStatusCode(400);
        }

        if (!is_null($name) && !is_null($email) && !is_null($pass)) {

            $user->update([
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make($pass)
            ]);

            return response()->json([
                'status' => true,
                'message'   => 'Operação realizada com sucesso!'
            ])->setStatusCode(201);
        }

        return response()->json([
            'status' => false,
            'message'   => 'Não foi possível realizar a operação!'
        ])->setStatusCode(400);
    }

    /**
     * Deleta um usuário do banco
     *
     * @OA\Delete  (
     *     path="/users/{id}",
     *     summary="UserController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do usuário",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     tags={"Controllers - Usuários"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$user = User::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $user->Company()?->delete();
        $user->Documents()?->delete();

        $user->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Registro removido com sucesso!'
        ])->setStatusCode(200);
    }
}
