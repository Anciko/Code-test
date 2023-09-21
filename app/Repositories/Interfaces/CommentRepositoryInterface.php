<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface CommentRepositoryInterface
{
   public function storeComment(Request $request, $id);
}
