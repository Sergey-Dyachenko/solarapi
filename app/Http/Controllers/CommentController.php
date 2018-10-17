<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;

class CommentController extends Controller
{
    public function show  ($id)      
    {
        return new CommentResource(Comment::find($id));
    }
}
