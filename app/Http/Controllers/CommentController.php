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
        return Comment::all()->groupBy('number');
    }

    public function create(Request $request)
    {   
        $last = Comment::orderBy('id', 'desc')->take(1)->get()->first(); 
        if (empty($last->number)){
            $number = 1;
            $path = 1; 
        }
        else{
            $number = $last->number + 1;
            $path = $number;  
        }  
        $comment = new Comment;
        $comment->name = $request->name;
        $comment->text = $request->text;
        $comment->number = $number;
        $comment->path =  $path;  
        $comment->save();       
        return Comment::all();       
    }

    public function reply(Request $request, $id)
    {
        $parent = Comment::findOrFail($id);
        if(empty(Comment::where('path', $parent->path . '.1')->get()->first())){   
            $number = $parent->number;
            $path = $parent->path . '.1';        
        }
        else{
            $last_child = Comment::where('path', 'like', $parent->path.'._')->orderBy('path', 'desc')->get()->first();
            $array_path = explode('.', $last_child->path);
            $count_array = count($array_path);
            $path_end_number = $array_path[$count_array-1];
            $path_end_number = $path_end_number + 1; 
            $number = $parent->number;
            $path = $parent->path . '.' . $path_end_number;
        }
        $comment = new Comment;
        $comment->name = $request->name;
        $comment->text = $request->text;
        $comment->number = $number;
        $comment->path =  $path;  
        $comment->save();       
        return Comment::all();       
         
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
