<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\UserResource;
use App\Http\Resources\Api\v1\UsersResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->user->query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return UsersResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return UserResource | JsonResponse
     */
    public function show(string $id): UserResource | JsonResponse
    {
        $user = User::query()->where('id', $id)->first();
        if ($user) return new UserResource($user);

        $statusCode = Response::HTTP_NOT_FOUND;
        $response = [
            'message' => ['Não existe usuário com esse id']
        ];
        return  response()->json($response, $statusCode);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $user = User::query()->where('id', $id)->first();
            if($user->id === Auth::user()->id){
                if(isset($data['email']) && $user->email !== $data['email']) {
                    $hasEmail = collect(User::query()->where('email', $data['email'])->get());
                    if(!$hasEmail->contains('email', $data['email'])){
                        $user->email = $data['email'];
                    }else{
                        DB::rollBack();
                        return response()->json(
                            [
                                'message' => 'Esse email já está sendo usado.',
                            ],
                            Response::HTTP_NOT_FOUND
                        );
                    }
                }

                if($request->password) {
                    $data['password'] = Hash::make($request->password);
                }
                else{
                    $data['password'] = $user['password'];
                }
                $user->update($data);
                DB::commit();
                $statusCode = Response::HTTP_CREATED;
                return response()->json($user, $statusCode);
            }
            DB::rollBack();
            $statusCode = Response::HTTP_FORBIDDEN;
            $response = [
                'message' => ['Você só pode atualizar o seu perfil']
            ];
            return  response()->json($response, $statusCode);

        }catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response = [
                'message' => ['Erro no servidor. Tente novamente mais tarde.']
            ];
            return response()->json($response, $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::query()->where('id', $id)->first();
            if($user->id === Auth::user()->id){
                Auth::user()->tokens()->delete();
                $user->delete();
                DB::commit();
                $statusCode = Response::HTTP_NO_CONTENT;
                return response()->json([], $statusCode);
            }
            DB::rollBack();
            $statusCode = Response::HTTP_FORBIDDEN;
            $response = [
                'message' => ['Você só pode deletar outro usuário']
            ];
            return  response()->json($response, $statusCode);

        }catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response = [
                'message' => ['Erro no servidor. Tente novamente mais tarde.']
            ];
            return response()->json($response, $statusCode);
        }
    }
}
