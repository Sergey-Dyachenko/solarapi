<?php

namespace Tests\Feature;
use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testListCommentsIndex()
    {  
        $response = $this->get('/api/comments');
        $response->assertStatus(200);
        // var_dump($response);
        // die();
    }

    public function testOneCommentShow()
    {
        $id_last = Comment::all()->last()->id;
        $id_not_exist = $id_last + 1;
        $response = $this->get('/api/comments/' . $id_last);
        $response->assertStatus(200);
        $response = $this->get('/api/comments/' . $id_not_exist);
        $response->assertStatus(204);
    }

    public function testCommentCreate()
    {
        $response = $this->json('POST', '/api/comments', ['name' => 'Test user', 'text' => 'Test message']);

        $response
        ->assertStatus(201)
        ->assertJsonFragment([
            'name' => 'Test user',
            'text' => 'Test message'
        ]);
    }

    public function testCommentDelete()
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
        $comment->name = 'Test name';
        $comment->text = 'Test text';
        $comment->number = $number;
        $comment->path =  $path;  
        $comment->save();       
        $this->json('DELETE', '/api/comments/'. $comment->id)
        ->assertStatus(204);
        
    }
}
