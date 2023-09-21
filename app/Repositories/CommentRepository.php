<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
  public function storeComment(Request $request, $id)
  {
    $comment = new Comment();
    $comment->movie_id = $id;
    $comment->description = $request->description;
    $comment->email = $request->email;
    $comment->commenter = $request->commenter;

    $comment->save();
  }
}
