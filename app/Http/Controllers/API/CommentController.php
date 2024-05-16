<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Response\Comment\SingleCommentResponse;
use App\Response\Comment\CommentCollectionResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentController extends Controller
{

    public function __construct(
        private AuthManager $auth,
        /* private CommentRequest $roleRequest */
    )
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Post $post) : CommentCollectionResponse | LengthAwarePaginator
    {
        return new CommentCollectionResponse(
            statusCode : 200,
            total : Comment::count(),
            message : "Liste des commentaires du post : " . $post->title,
            collection : Comment::query()->with(['post', 'user'])->where('post_id', $post->id)->paginate(20),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Post $post, CommentRequest $request) : SingleCommentResponse
    {
        $comment = Comment::create(array_merge(
            $request->validated(), [
                'post_id' => $post->id,
                'user_id' => $this->auth->user()->id,
            ]
        ));

        return new SingleCommentResponse(
            statusCode : 200,
            message : "Commentaire crée avec succès",
            resource : new CommentResource(resource : Comment::query()->with(['post', 'user'])->where('id', $comment->id)->first())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Comment $comment) : SingleCommentResponse
    {
        return new SingleCommentResponse(
            statusCode : 200,
            message : "Informations sur le commentaire : " . $comment->id . "du post : " . $post->title,
            resource : new CommentResource(resource : Comment::query()->with(['post', 'user'])->where('id', $comment->id)->first())
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, Comment $comment, CommentRequest $request) : SingleCommentResponse
    {
        $comment->update($request->validated());

        return new SingleCommentResponse(
            statusCode : 200,
            message : "Commentaire édité avec succès",
            resource : new CommentResource(resource : Comment::query()->with(['post', 'user'])->where('id', $comment->id)->first())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment) : JsonResponse
    {
        $comment->delete();
        return response()->json([
            'status' => 200,
            'message' => "Commentaire supprimé avec succès",
        ]);
    }
}
