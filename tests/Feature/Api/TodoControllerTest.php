<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }


    /**
     * @test
     */
    public function Todoの新規作成()
    {   
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);

    }

    
    /**
     * @test
     */
    public function Todoの更新()
    {   
        //$paramsで代入したデータで更新できるかテスト
        $params = [
            'title' => 'テスト:タイトル2',
            'content' => 'テスト:内容2'
        ];

        $this->assertDatabaseMissing('todos', $params);

        $res = $this->putJson(route('api.todo.index'), $params);
        
        $res->assertStatus(500);

    }

    /**
     * @test
     */
    public function Todoの詳細取得()
    {   
        //$paramsで代入したデータで取得できるかテスト
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->getJson(route('api.todo.edit'), $params);


        $res->assertStatus(500);
    }

    /**
     * @test
     */
    public function Todoの削除()
    {   
        //$paramsで代入したデータをDBに格納後、削除処理をしたらDBから消えているか。物理処理なのでDBから消えているかテスト

        $params =[
            'title' => 'テスト:タイトル削除',
            'content' => 'テスト:内容削除'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);

        $this->assertDatabaseHas('todos', $params);

        $res = $this->deleteJson(route('api.todo.destroy'), $params);

        //上記で削除処理をしたので、DBに$paramsデータはないはず…
        $this->assertDatabaseMissing('todos', $params);
        
    }

    

}