<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\CommentCollection;

class CommentController extends Controller
{
    public function show($id)      
    {
        return new CommentResource(Comment::find($id));
    }

    public function index()      
    {
        return Comment::all();пше 
    }

    public function create(Request $request)
    {
        return Comment::create($request->all());       
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());
        return $comment;
    }

    public function delete(Request $request, $id){
         $comment = Comment::findOrFail($id);
         $comment->delete();
         return 204;
    }
}
