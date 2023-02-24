<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


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
    public function Todoの新規作成失敗()
    {   //タイトル未入力、文字列以外を登録した場合にエラーが出る
        $params = [
            'content' => null,
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
    public function Todoの更新失敗()
    {   //$paramsで代入したデータで更新できるかテスト

        $id = Todo::factory()->createOne()->id;

        $params = [
            'content' => null,
        ];

        $res = $this->putJson(route('api.todo.update', ['id' => $id]), $params);
        
        $res->assertStatus(404);

        $editedData = Todo::find($id);
        
        $this->assertEquals($params['title'], $editedData->title);
        $this->assertEquals($params['content'], $editedData->content);

    }

    /**
     * @test
     */
    public function Todoの詳細取得失敗()
    {   //レスポンスが200以外のステータスコードを持っているか

        $id = Todo::factory()->createOne()->id;

        $res = $this->getJson(route('api.todo.show', ['id' => $id]) );

        $res->assertStatus(404);
    }

    /**
     * @test
     */
    public function Todoの削除失敗()
    {   //削除したデータがfindで取得できないはず

        $id = Todo::factory()->createOne()->id;

        $res = $this->deleteJson(route('api.todo.destroy', ['id' => $id]));

        $res->assertStatus(404);

        $editedData = Todo::find($id);
    }

    

}