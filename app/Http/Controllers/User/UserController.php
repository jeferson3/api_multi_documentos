<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(User::all()->toArray())->setStatusCode(200);
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
        $email = $request->get('email');
        $pass  = $request->get('password');
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
     * Display the specified resource.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        if (is_null($user) || empty($user)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        return response()->json($user)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user)
    {

        if (is_null($user) || empty($user)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Nenhum registro encontrado!'
            ])->setStatusCode(404);
        }

        $name       = $request->get('name');
        $email      = $request->get('email');
        $pass       = $request->get('password');
        $company_id = $request->get('company_id');

        if (!is_null($name) && !is_null($email) && !is_null($pass) && !is_null($company_id)) {

            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
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
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        if (is_null($user) || empty($user)) {
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
