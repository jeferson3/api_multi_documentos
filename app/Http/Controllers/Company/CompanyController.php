<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('gerente');
    }

    /**
     *  Retorna todos as empresas do banco
     *
     * @OA\Get (
     *     path="/companies",
     *     summary="CompanyController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     tags={"Controllers - Empresas"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Company::where('user_id', auth()->user()->id)->first())->setStatusCode(200);
    }

    /**
     * Salva uma nova empresa no banco
     *
     * @OA\Post (
     *     path="/companies",
     *     summary="CompanyController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="400", description="Resposta com erro"),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar empresa",
     *          @OA\JsonContent(
     *              @OA\Property(property="cpf_cnpj", type="string", example="11.111.111/0001-11"),
     *              @OA\Property(property="name", type="string", example="Nome da empresa"),
     *              @OA\Property(property="description", type="string", example="Descrição da empresa"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Empresas"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $cpf_cnpj    = $request->get('cpf_cnpj');
        $name        = $request->get('name');
        $description = $request->get('description');

        if (is_null($cpf_cnpj) || is_null($name) || is_null($description)) {
            return response()->json([
                'status' => false,
                'message'   => 'Não foi possível realizar a operação!'
            ])->setStatusCode(400);
        }

        if (Company::whereCpfCnpj($cpf_cnpj)->exists()){
            return response()->json([
                'status' => false,
                'message'   => 'Este CPF/CNPJ já está cadastrado em nosso sistema!'
            ])->setStatusCode(400);
        }

        Company::create([
            'cpf_cnpj'    => $cpf_cnpj,
            'name'        => $name,
            'description' => $description,
            'user_id'     => auth()->user()->id
        ]);

        // mudando o perfil do usuário logado de usuário do sistema para gerente de empresa
        $user = auth()->user();
        $user->profile_id = 2;
        $user->save();

        return response()->json([
            'status' => true,
            'message'   => 'Operação realizada com sucesso!'
        ])->setStatusCode(201);
    }

    /**
     * Retorna uma empresa específico pelo ID
     *
     * @OA\Get (
     *     path="/companies/{id}",
     *     summary="CompanyController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do empresa",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     tags={"Controllers - Empresas"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        if (!$company = Company::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        return response()->json($company)->setStatusCode(200);
    }

    /**
     * Atualiza uma empresa no banco
     *
     *  @OA\Put (
     *     path="/companies/{id}",
     *     summary="CompanyController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="400", description="Resposta com erro"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do empresa",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar empresa",
     *          @OA\JsonContent(
     *              @OA\Property(property="cpf_cnpj", type="string", example="11.111.111/0001-11"),
     *              @OA\Property(property="name", type="string", example="Nome da empresa"),
     *              @OA\Property(property="description", type="string", example="Descrição da empresa"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Empresas"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  Request  $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {

        if (!$company = Company::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $cpf_cnpj    = $request->get('cpf_cnpj');
        $name  = $request->get('name');
        $description = $request->get('description');

        if (Company::whereCpfCnpj($cpf_cnpj)->exists() && $company->cpf_cnpj != $cpf_cnpj){
            return response()->json([
                'status' => false,
                'message'   => 'Este CPF/CNPJ já está cadastrado em nosso sistema!'
            ])->setStatusCode(400);
        }

        if (!is_null($name) && !is_null($description)) {

            $company->update([
                'cpf_cnpj'    => $cpf_cnpj,
                'name'        => $name,
                'description' => $description,
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
     * Deleta uma empresa do banco
     *
     * @OA\Delete  (
     *     path="/companies/{id}",
     *     summary="CompanyController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do empresa",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     tags={"Controllers - Empresas"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$company = Company::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $company->delete();

        // mudando o perfil do usuário logado de gerente de empresa para usuário do sistema
        $user = auth()->user();
        $user->profile_id = 3;
        $user->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Registro removido com sucesso!'
        ])->setStatusCode(200);
    }
}
