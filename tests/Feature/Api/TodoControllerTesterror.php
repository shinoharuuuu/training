<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;


class TodoControllerTesterror extends TestCase
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
    {   //タイトル未入力、文字列以外を登録した場合にエラーが出る
        $params = [
            'content' => [],
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

        $this->assertDatabaseMissing('todos', $params);
        
        $res->assertOk();

    }

    /**
     * @test
     */
    public function Todoの詳細取得()
    {   
        //$paramsで代入したデータ取得のHTTPステータスコードは新規作成と異なるはず
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->getJson(route('api.todo.edit'), $params);


        $res->assertOk();
    }

    /**
     * @test
     */
    public function Todoの削除()
    {   
        //$paramsで代入したデータをDBに格納後、削除処理をしたら論理削除されているか

        $params =[
            'title' => 'テスト:タイトル削除',
            'content' => 'テスト:内容削除'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);

        $this->assertDatabaseHas('todos', $params);

        $res = $this->deleteJson(route('api.todo.destroy'), $params);

        $this->assertSoftDeleted(table:'todos' , data: ['title' => 'テスト:タイトル削除']);
        $this->assertSoftDeleted(table:'todos' , data: ['content' => 'テスト:内容削除']);
        
    }

    

}