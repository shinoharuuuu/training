<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

        $id = Todo::factory()->createOne()->id;

        $params = [
            'title' => 'テスト:タイトル2',
            'content' => 'テスト:内容2'
        ];

        $res = $this->putJson(route('api.todo.update', ['id' => $id]), $params);
        
        $res->assertOk();

        $todo = Todo::find($id);

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);
    }

    /**
     * @test
     */
    public function Todoの詳細取得()
    {   //レスポンスが200のステータスコードを持っているか
        
        $id = Todo::factory()->createOne()->id;

        $res = $this->getJson(route('api.todo.show', ['id' => $id]) );


        $res->assertOk();
    }

    /**
     * @test
     */
    public function Todoの削除()
    {   //データが削除処理をしたら消えていることが確認できるか

        $id = Todo::factory()->createOne()->id;

        $res = $this->deleteJson(route('api.todo.destroy', ['id' => $id]));

        $res->assertOk();

        $this->assertNull(Todo::find($id));
        
    }

    

}