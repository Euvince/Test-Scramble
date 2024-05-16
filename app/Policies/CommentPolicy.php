<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{

    public function __construct(
        private readonly AuthManager $auth
    )
    {
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return
            in_array('admin', $user->roles()->get()->pluck('name', 'id')->toArray()) ||
            $comment->user_id === $this->auth->user()->id
        ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return
            in_array('admin', $user->roles()->get()->pluck('name', 'id')->toArray()) ||
            $comment->user_id === $this->auth->user()->id ||
            $this->auth->user()->posts->contains($comment->post)
        ;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return
            in_array('admin', $user->roles()->get()->pluck('name', 'id')->toArray())
        ;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return
            in_array('admin', $user->roles()->get()->pluck('name', 'id')->toArray()) ||
            $comment->user_id === $this->auth->user()->id ||
            $this->auth->user()->posts->contains($comment->post)
        ;
    }
}
