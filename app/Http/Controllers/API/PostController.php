<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Responses\Post\SinglePostResponse;
use App\Http\Responses\Post\PostCollectionResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostController extends Controller
{

    public function __construct(
        private readonly AuthManager $auth,
        /* private readonly PostRequest $postRequest */
    )
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() : PostCollectionResponse | LengthAwarePaginator
    {
        return new PostCollectionResponse(
            statusCode : 200,
            total : Post::count(),
            message : "Liste des publications",
            collection : Post::query()->with(['user', 'comments'])->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request) : SinglePostResponse
    {
        $post = Post::create(array_merge($request->validated(), ['user_id' => $this->auth->user()->id]));

        return new SinglePostResponse(
            statusCode : 200,
            message : "Publication créee avec succès",
            resource : new PostResource(resource : Post::query()->with('user')->where('id', $post->id)->first())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) : SinglePostResponse
    {
        return new SinglePostResponse(
            statusCode : 200,
            message : "Informations de la publication",
            resource : new PostResource(resource : Post::query()->with(['user', 'comments'])->where('id', $post->id)->first())
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return new SinglePostResponse(
            statusCode : 200,
            message : "Publication éditée avec succès",
            resource : new PostResource(resource : Post::query()->with(['user', 'comments'])->where('id', $post->id)->first())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) : JsonResponse
    {
        $post->delete();
        return response()->json([
            'status' => 200,
            'message' => "Publication supprimée avec succès",
        ]);
    }
}
