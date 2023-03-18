<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            DB::commit();
            $statusCode = Response::HTTP_CREATED;
            $response = [
                'message' => ['Usuário Cadastrado com sucesso.'],
                'token' => $token,
            ];
            return response()->json($response, $statusCode);

        }catch (\Throwable $e){
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response = [
                'message' => ['Erro no servidor. Tente novamente cadastrar um usuário.']
            ];
            return response()->json($response, $statusCode);
        }

    }
}
