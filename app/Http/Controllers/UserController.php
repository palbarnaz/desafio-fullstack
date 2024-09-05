<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|string',
                    'email' => 'required|unique:users,email',
                    'password' => 'required|string'
                ],
                [
                    'required' => 'O campo :attribute é obrigatório.',
                    'string' => 'O campo :attribute precisa ser uma string.',
                    'unique' => 'O :attribute já está em uso.',

                ]
            );


            if (!UserService::isStrongPassword($request->password)) {
                return response()->json(['success' => false, 'message' => 'A senha precisa ser forte.'], Response::HTTP_BAD_REQUEST);
            }

            if (!UserService::isValidEmailFormat($request->email)) {
                return response()->json(['success' => false, 'message' => 'O e-mail precisa ser válido.'], Response::HTTP_BAD_REQUEST);
            }

            $requestData = $request->all();

            $requestData['password'] = Hash::make($requestData['password']);


            $user = User::create($requestData);

            return response()->json([
                'message' => 'Usuario criado com sucesso!',
                'user' => $user
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            // Tratar exceções de validação
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QueryException $e) {
            // Tratar exceções de consulta ao banco de dados
            return response()->json([
                'message' =>  $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
