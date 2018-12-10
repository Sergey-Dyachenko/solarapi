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
}
