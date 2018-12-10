<?php

namespace App\Http\Controllers;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\CommentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentController extends Controller
{
    public function render ($request, Exception $exception)
    {
        if($request->wantsJson() && $exception instanceof ModelNotFoundException)
        {
            return response()->json(['status' => 'object request not found'], 404);
        }
    }

    public function show($id)      
    {
       $comment = Comment::find($id);
       if($comment){
            return response()->json($comment, 200);
        }
        else{
            return response()->json($comment, 204);
        }

    }

    public function index() 
    {   
        return Comment::all()->groupBy('number');
    }

    public function store(Request $request)
    {   
        $last = Comment::orderBy('number', 'desc')->take(1)->get()->first(); 
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
        return response()->json($comment, 201);       
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
         $child_comments = Comment::where('path', 'like', $comment->path . '.%')->get()->all();
         if (!(empty($child_comments))){
            foreach ($child_comments as $child_comment){
                 $child_comment->delete();    
             }
            
         }
        $path = $comment->path; 
        $comment->delete();
        $array_path = explode('.', $path);
        array_pop($array_path);
        if (!(empty($array_path)))
        {
            $string = implode('' , $array_path);
            $string_path = $string . '._' ;
            $siblings = Comment::where('path', 'like',  $string_path)->orderBy('path', 'desc')->get();
            $i = 1;
            foreach ($siblings as $sibling) :
                $array_path = explode( '.' ,$sibling->path);
                array_pop($array_path);
                $path = implode('.', $array_path);
                $sibling->path = $path .'.'. $i;
                $i++;
                $sibling->save();
            endforeach;
        }    
         return 204;
    }
}
