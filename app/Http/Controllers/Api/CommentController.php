<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Responder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    private $comment_repository;
    public function __construct(CommentRepositoryInterface $comment_repository)
    {
        $this->comment_repository = $comment_repository;
    }

    /**
     * Store comment
     */
    public function store(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'email' => 'required|email',
            'commenter' => 'required'
        ]);

        if ($validator->fails()) {
            return Responder::fail('Validation error!', $validator->errors()->all(), 422);
        }

      $this->comment_repository->storeComment($request, $id);

       return Responder::success('Comment created successfully!');
    }
}
