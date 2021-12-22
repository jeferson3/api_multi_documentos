<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Retorna todos os documentos do banco
     *
     * @OA\Get (
     *     path="/documents",
     *     summary="DocumentController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     tags={"Controllers - Documentos"},
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        // se for administrador geral retorna todos os documentos do banco
        if ($user->getPermission() === 9) return response()->json(Document::all());

        // caso contrário retorna somente os documentos que pertencem ao usuário logado
        return response()->json(Document::where('user_id', auth()->user()->id)->get()->toArray())->setStatusCode(200);
    }

    /**
     * Salva um novo documento no banco
     *
     * @OA\Post (
     *     path="/documents",
     *     summary="DocumentController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar documento",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="CPF"),
     *              @OA\Property(property="type", type="string", example="identificação"),
     *              @OA\Property(property="description", type="string", example="Cadastro de Pessoa Física"),
     *              @OA\Property(property="id_number", type="string", example="111.111.111-11"),
     *              @OA\Property(property="issue_date", type="string", example="2021-12-12"),
     *              @OA\Property(property="issuing_body", type="string", example="Receita Federal"),
     *              @OA\Property(property="country_issuing", type="string", example="Brasil"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Documentos"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $name  = $request->get('name');
        $type = $request->get('type');
        $description = $request->get('description');
        $id_number = $request->get('id_number');
        $issue_date = $request->get('issue_date');
        $issuing_body = $request->get('issuing_body');
        $country_issuing = $request->get('country_issuing');

        if (!is_null($name) && !is_null($type) && !is_null($description)
            && !is_null($id_number) && !is_null($issue_date) && !is_null($issuing_body) && !is_null($country_issuing)) {

            if (Document::where('id_number', $id_number)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'A identificação já está cadastrada no nosso sistema!'
                ])->setStatusCode(400);
            }

            Document::create([
                'name'              => $name,
                'user_id'           => auth()->user()->id,
                'type'              => $type,
                'description'       => $description,
                'id_number'         => $id_number,
                'issue_date'        => $issue_date,
                'issuing_body'        => $issuing_body,
                'country_issuing'   => $country_issuing

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
     * Retorna um documento específico pelo ID
     *
     * @OA\Get (
     *     path="/documents/{id}",
     *     summary="DocumentController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do documento",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     tags={"Controllers - Documentos"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if (!$document = Document::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        return response()->json($document)->setStatusCode(200);
    }

    /**
     * Atualiza um documento no banco
     *
     *  @OA\Put (
     *     path="/documents/{id}",
     *     summary="DocumentController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do documento",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          description="Salvar documento",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="CPF"),
     *              @OA\Property(property="type", type="string", example="identificação"),
     *              @OA\Property(property="description", type="string", example="Cadastro de Pessoa Física"),
     *              @OA\Property(property="id_number", type="string", example="111.111.111-11"),
     *              @OA\Property(property="issue_date", type="string", example="2021-12-12"),
     *              @OA\Property(property="issuing_body", type="string", example="Receita Federal"),
     *              @OA\Property(property="country_issuing", type="string", example="Brasil"),
     *          )
     *     ),
     *
     *     tags={"Controllers - Documentos"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  Request  $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(Request $request, int$id): JsonResponse
    {

        if (!$document = Document::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $name            = $request->get('name');
        $type            = $request->get('type');
        $description     = $request->get('description');
        $id_number       = $request->get('id_number');
        $issue_date      = $request->get('issue_date');
        $issuing_body      = $request->get('issuing_body');
        $country_issuing = $request->get('country_issuing');

        if (Document::where('id_number', $id_number)->exists() && $document->id_number != $id_number) {
            return response()->json([
                'status' => false,
                'message' => 'A identificação já está cadastrada no nosso sistema!'
            ])->setStatusCode(400);
        }

        if (!is_null($name) && !is_null($type) && !is_null($description) && !is_null($id_number)
            && !is_null($issue_date) && !is_null($issuing_body) && !is_null($country_issuing)) {

            $document->update([
                'name'              => $name,
                'type'              => $type,
                'description'       => $description,
                'id_number'         => $id_number,
                'issue_date'        => $issue_date,
                'issuing_body'        => $issuing_body,
                'country_issuing'   => $country_issuing
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
     * Deleta um documento do banco
     *
     * @OA\Delete  (
     *     path="/documents/{id}",
     *     summary="DocumentController",
     *     @OA\Response(response="200", description="Resposta com sucesso"),
     *     @OA\Response(response="404", description="Resposta com erro"),
     *
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do documento",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     tags={"Controllers - Documentos"},
     *     security={{ "apiAuth": {} }}
     * )
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$document = Document::find($id)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $document->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Registro removido com sucesso!'
        ])->setStatusCode(200);
    }
}
