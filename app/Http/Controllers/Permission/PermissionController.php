<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\Document\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Psy\Util\Json;

class PermissionController extends Controller
{
    /**
     * Retorna todas as permissões do banco
     *
     * @OA\Get (
     *     path="/permissions",
     *     summary="PermissionController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     tags={"Controllers - Permissões"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $res = DB::table('profile_permission')
            ->selectRaw('users.name as user, profiles.name as profile, permissions.value as level_permission')
            ->join('profiles', 'profile_permission.profile_id', '=', 'profiles.id')
            ->join('permissions', 'profile_permission.permission_id', '=', 'permissions.id')
            ->join('users', 'profiles.id', '=', 'users.profile_id')
            ->get();
        return response()->json($res)->setStatusCode(200);
    }

    /**
     * Salva uma nova permissão para um perfil
     *
     * @OA\Post (
     *     path="/permissions/setPermission",
     *     summary="PermissionController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="400", description="Resposta de erro"),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar permissão",
     *          @OA\JsonContent(
     *              @OA\Property(property="permission_id", type="int", example="9"),
     *              @OA\Property(property="profile_id", type="int", example="1"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Permissões"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  Request  $request
     * @return JsonResponse
     */
    public function setPermission(Request $request): JsonResponse
    {

        $permission_id  = $request->get('permission_id');
        $profile_id     = $request->get('profile_id');

        if (!is_null($permission_id) && !is_null($profile_id)) {

            try {

                DB::table('profile_permission')->insert([
                    'permission_id' => $permission_id,
                    'profile_id'    => $profile_id,
                ]);

                return response()->json([
                    'status' => true,
                    'message'   => 'Operação realizada com sucesso!'
                ])->setStatusCode(201);
            }
            catch (\Exception $exception){

                return response()->json([
                    'status'    => false,
                    'message'   => 'Não foi possível realizar a operação!',
                    'error'     => $exception->getMessage()
                ])->setStatusCode(400);
            }
        }

        return response()->json([
            'status' => false,
            'message'   => 'Não foi possível realizar a operação!'
        ])->setStatusCode(400);
    }

    /**
     * Remove uma permissão de um perfil
     *
     *  @OA\Post (
     *     path="/permissions/removePermission",
     *     summary="PermissionController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="400", description="Resposta de erro"),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Remover permissão",
     *          @OA\JsonContent(
     *              @OA\Property(property="permission_id", type="int", example="9"),
     *              @OA\Property(property="profile_id", type="int", example="1"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Permissões"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function removePermission(Request $request): JsonResponse
    {

        $permission_id  = $request->get('permission_id');
        $profile_id     = $request->get('profile_id');

        if (!is_null($permission_id) && !is_null($profile_id)) {

            try {

                DB::table('profile_permission')
                    ->where('permission_id', $permission_id)
                    ->where('profile_id', $profile_id)
                    ->delete();

                return response()->json([
                    'status' => true,
                    'message'   => 'Operação realizada com sucesso!'
                ])->setStatusCode(201);
            }
            catch (\Exception $exception){

                return response()->json([
                    'status'    => false,
                    'message'   => 'Não foi possível realizar a operação!',
                    'error'     => $exception->getMessage() // salvar um log do erro nesse ponto seria ideal
                ])->setStatusCode(400);
            }
        }

        return response()->json([
            'status' => false,
            'message'   => 'Não foi possível realizar a operação!'
        ])->setStatusCode(400);
    }

}
