<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Document::all()->toArray())->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $name  = $request->get('name');
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        $description = $request->get('description');
        $id_number = $request->get('id_number');
        $issue_date = $request->get('issue_date');
        $issue_body = $request->get('issue_body');
        $country_issuing = $request->get('country_issuing');

        if (!is_null($name) && !is_null($user_id) && !is_null($type) && !is_null($description)
            && !is_null($id_number) && !is_null($issue_date) && !is_null($issue_body) && !is_null($country_issuing)) {

            Document::create([
                'name'              => $name,
                'user_id'           => $user_id,
                'type'              => $type,
                'description'       => $description,
                'id_number'         => $id_number,
                'issue_date'        => $issue_date,
                'issue_body'        => $issue_body,
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
     * Display the specified resource.
     *
     * @param  Document  $document
     * @return JsonResponse
     */
    public function show(Document $document)
    {
        if (is_null($document) || empty($document)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        return response()->json($document)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Document  $document
     * @return JsonResponse
     */
    public function update(Request $request, Document $document)
    {

        if (is_null($document) || empty($document)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $name  = $request->get('name');
        $type = $request->get('type');
        $description = $request->get('description');
        $id_number = $request->get('id_number');
        $issue_date = $request->get('issue_date');
        $issue_body = $request->get('issue_body');
        $country_issuing = $request->get('country_issuing');

        if (!is_null($name) && !is_null($type) && !is_null($description) && !is_null($id_number)
            && !is_null($issue_date) && !is_null($issue_body) && !is_null($country_issuing)) {

            $document->update([
                'name'              => $name,
                'type'              => $type,
                'description'       => $description,
                'id_number'         => $id_number,
                'issue_date'        => $issue_date,
                'issue_body'        => $issue_body,
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
     * Remove the specified resource from storage.
     *
     * @param  Document  $document
     * @return JsonResponse
     */
    public function destroy(Document $document)
    {
        if (is_null($document) || empty($document)) {
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
