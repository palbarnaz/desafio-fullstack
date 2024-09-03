<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Services\MentorService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $query = Mentor::query();

            $cpf = $request->query('cpf');
            $email = $request->query('email');
            $name = $request->query('name');


            if ($cpf) {
                $query->where('cpf', 'LIKE', "%{$cpf}%");
            }

            if ($email) {
                $query->where('email', 'LIKE', "%{$email}%");
            }

            if ($name) {
                $query->where('name', 'LIKE', "%{$name}%");
            }
            $mentors = $query->get();

            // $mentors = $query->paginate(2);
            return response()->json([
                'mentores' => $mentors
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao recuperar os dados. Por favor, tente novamente.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
            $cpf = MentorService::removeCpfMask($request->cpf);
            $request->merge(['cpf' => $cpf]);

            $request->validate(
                [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:mentors,email',
                    'cpf' => 'required|string|unique:mentors,cpf',

                ],
                [
                    'required' => 'O campo :attribute é obrigatório.',
                    'string' => 'O campo :attribute precisa ser uma string.',
                    'email' => 'O campo :attribute precisa ser um e-mail válido.',
                    'size' => 'O campo :attribute deve ter exatamente :size caracteres.',
                    'unique' => 'O :attribute já está em uso.',

                ]
            );


            if (!MentorService::isValidCPF($cpf)) {
                return response()->json(['success' => false, 'msg' => 'O CPF precisa ser válido.'], Response::HTTP_BAD_REQUEST);
            }

            if (!MentorService::isValidEmailFormat($request->email)) {
                return response()->json(['success' => false, 'msg' => 'O E-mail precisa ser válido.'], Response::HTTP_BAD_REQUEST);
            }

            $mentor = Mentor::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Mentor criado com sucesso!',
                'mentor' => $mentor
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            // Tratar exceções de validação
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QueryException $e) {
            // Tratar exceções de consulta ao banco de dados
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $mentor = Mentor::findOrFail($id);

            return response()->json([
                'mentor' => $mentor
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Mentor não encontrado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao recuperar os dados. Por favor, tente novamente.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|string',
                    'email' => 'required|email',
                ],
                [
                    'required' => 'O campo :attribute é obrigatório.',
                    'string' => 'O campo :attribute precisa ser uma string.',
                    'email' => 'O campo :attribute precisa ser um e-mail válido.',
                ]
            );

            $existingMentor = Mentor::where('email', $request->email)->where('id', '!=', $id)->first();
            if ($existingMentor) {
                return response()->json([
                    'message' => 'O e-mail já está em uso por outro mentor.'
                ], Response::HTTP_CONFLICT);
            }


            $mentor = Mentor::findOrFail($id);
            $mentor->update($request->all());

            return response()->json([
                'message' => 'Mentor atualizado com sucesso!',
                'mentor' => $mentor
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Mentor não encontrado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao atualizar os dados. Por favor, tente novamente.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $mentor = Mentor::findOrFail($id);
            $mentor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mentor excluído com sucesso!'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mentor não encontrado.'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao excluir os dados. Por favor, tente novamente.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
