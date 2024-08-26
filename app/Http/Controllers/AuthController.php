<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
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
                    'email' => 'required|email',
                    'password' => 'required|string',


                ],
                [
                    'required' => 'O campo :attribute é obrigatório.',
                    'string' => 'O campo :attribute precisa ser uma string.',
                    'email' => 'O campo :attribute precisa ser um e-mail válido.',


                ]
            );

            $user = User::where('email', $request->email)->first();


            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'E-mail e/ou senha inválidos.',
                ], Response::HTTP_UNAUTHORIZED);
            };
            $token = $user->createToken($user->email)->plainTextToken;


            return response()->json([
                'message' => 'Login feito com sucesso!',
                'user' => $user,
                "token" => $token
            ], Response::HTTP_OK);
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
