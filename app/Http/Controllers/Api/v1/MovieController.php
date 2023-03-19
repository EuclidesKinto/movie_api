<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\MovieStoreRequest;
use App\Http\Resources\Api\v1\MovieResource;
use App\Http\Resources\Api\v1\MoviesResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $movies = Movie::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return MoviesResource::collection($movies);
    }

    /**
     * Store a newly created resource in storage.
     * @param MovieStoreRequest $request
     * @return JsonResponse
     */
    public function store(MovieStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            if($request->hasFile('image')){
                $path = $request->file('image')->store('public/movies');
            }
            Movie::query()->create([
                'name' => $request->name,
                'image' => $path,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            $statusCode = Response::HTTP_CREATED;
            $response = [
                'message' => ['Filme cadastrado com sucesso.']
            ];
            return response()->json($response, $statusCode);

        }catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response = [
                'message' => ['Erro no servidor. Tente novamente cadastrar um Filme.']
            ];
            return response()->json($response, $statusCode);
        }
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return MovieResource|JsonResponse
     */
    public function show(string $id): MovieResource | JsonResponse
    {
        $movie = Movie::query()->where('id', $id)->first();
        if ($movie) return new MovieResource($movie);

        $statusCode = Response::HTTP_NOT_FOUND;
        $response = [
            'message' => ['Não existe filme com esse id']
        ];
        return  response()->json($response, $statusCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//                dd($request->all(), $id);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $movie = Movie::query()->where('id', $id)
                ->with('user')
                ->first();
//            dd(Auth::user()->id === $movie->user_id);
            if(Auth::user()->id === $movie->user_id){
                if($data['image']){
                    if(Storage::disk('public')->exists('movies/'.$movie->image)){
                        Storage::disk('public')->delete('movies/'.$movie->image);
                    }
                    $path = $request->file('image')->store('public/movies');
                    $data['image'] = $path;
                }else{
                    $data['image'] = $movie->image;
                }

                $movie->update($data);
                DB::commit();
                $statusCode = Response::HTTP_CREATED;
                $response = [
                    'message' => ['Filme atualizado com sucesso.']
                ];
                return response()->json($response, $statusCode);

            }
            DB::rollBack();
            $statusCode = Response::HTTP_FORBIDDEN;
            $response = [
                'message' => ['Você só pode atualizar o seu filme']
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
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id):JsonResponse
    {
        DB::beginTransaction();
        try {
            $movie = Movie::query()->where('id', $id)->first();
//        dd($movie->user_id, Auth::user()->id);
            if($movie->user_id === Auth::user()->id){
                $movie->delete();
                DB::commit();
                $statusCode = Response::HTTP_NO_CONTENT;
                return response()->json([], $statusCode);
            }
            DB::rollBack();
            $statusCode = Response::HTTP_FORBIDDEN;
            $response = [
                'message' => ['Você não pode deletar esse Filme']
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
