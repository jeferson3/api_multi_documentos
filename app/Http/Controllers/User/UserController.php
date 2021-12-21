<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar usuário",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="String", example="admin"),
     *              @OA\Property(property="email", type="String", example="admin@email.com"),
     *              @OA\Property(property="password", type="String", example="password"),
     *              @OA\Property(property="company_id", type="Int", example=1),
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
        $company_id  = $request->get('company_id');

        if (!is_null($name) && !is_null($email) && !is_null($pass) && !is_null($company_id)) {

            User::create([
                'name'       => $name,
                'email'      => $email,
                'password'   => $pass,
                'company_id' => $company_id,
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
     *              @OA\Property(property="name", type="String", example="admin"),
     *              @OA\Property(property="email", type="String", example="admin@email.com"),
     *              @OA\Property(property="password", type="String", example="password"),
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

        $name       = $request->get('name');
        $email      = $request->get('email');
        $pass       = $request->get('password');

        if (!is_null($name) && !is_null($email) && !is_null($pass)) {

            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $request->get('password')
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

        $user->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Registro removido com sucesso!'
        ])->setStatusCode(200);
    }
}
