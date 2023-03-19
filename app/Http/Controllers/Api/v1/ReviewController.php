<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $movie = Movie::query()->where('id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function store(Request $request, string $id): JsonResponse
    {

        try {
            $movie = Movie::query()->where('id', $id)->first();
            if($movie){
                DB::transaction(function() use ($request, $movie) {
                    Review::query()->create([
                        'name' => Auth::user()->name,
                        'rating' => $request->rating,
                        'comment' => $request->comment,
                        'user_id' => Auth::user()->id,
                        'movie_id' =>  $movie->id
                    ]);
                });
                $statusCode = Response::HTTP_CREATED;
                $response = [
                    'message' => ['Review criado com sucesso']
                ];
                return  response()->json($response, $statusCode);
            }
            $statusCode = Response::HTTP_NOT_FOUND;
            $response = [
                'message' => ['Não existe filme com esse id']
            ];
            return  response()->json($response, $statusCode);

        }catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '=>' . $e->getMessage());
            $statusCode = Response::HTTP_BAD_REQUEST;
            $response = [
                'message' => ['Erro no servidor. Tente novamente cadastrar um Review.']
            ];
            return response()->json($response, $statusCode);
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
