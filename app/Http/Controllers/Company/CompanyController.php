<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Company::all()->toArray())->setStatusCode(200);
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
        $description = $request->get('description');


        if (!is_null($name) && !is_null($description)) {

            Company::create([
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
     * Display the specified resource.
     *
     * @param  Company  $company
     * @return JsonResponse
     */
    public function show(Company $company)
    {
        if (is_null($company) || empty($company)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        return response()->json($company)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Company  $company
     * @return JsonResponse
     */
    public function update(Request $request, Company $company)
    {

        if (is_null($company) || empty($company)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }
        $name  = $request->get('name');
        $description = $request->get('description');


        if (!is_null($name) && !is_null($description)) {

            $company->update([
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
     * Remove the specified resource from storage.
     *
     * @param  Company  $company
     * @return JsonResponse
     */
    public function destroy(Company $company)
    {
        if (is_null($company) || empty($company)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $company->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Registro removido com sucesso!'
        ])->setStatusCode(200);
    }
}
