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
     * 
     */
    public function test_新規作成失敗()
    {   
        //タイトル未入力、文字列以外を登録した場合に処理ができない(422)ステータスコードが返ってくる

        $params = [
            'content' => null,
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertStatus(422);
    }

    /**
     *
     */
    public function test_新規作成()
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
     *
     */
    public function test_更新失敗()
    {   
        //タイトル未入力、文字列以外で更新した場合に処理ができない(422)ステータスコードが返ってくる

        $id = Todo::factory()->createOne()->id;

        $params = [
            'content' => null,
        ];

        $res = $this->putJson(route('api.todo.update', ['id' => $id]), $params);
        
        $res->assertStatus(422);

    }
    
    /**
     *
     */
    public function test_更新()
    {   
        //$paramsで代入した値で$idを更新できる

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
     *
     */
    public function test_詳細取得失敗()
    {  
         //詳細取得する$idが見つからない

        $id = Todo::factory()->createOne()->id;

        $res = $this->getJson(route('api.todo.edit', ['id' => $id + 1]));

        $res->assertStatus(404);
    }

    /**
     *
     */
    public function test_詳細取得()
    {  
        //詳細取得処理をしたレスポンスが200のステータスコードを持っているか
        
        $id = Todo::factory()->createOne()->id;

        $res = $this->getJson(route('api.todo.edit', ['id' => $id]) );


        $res->assertOk();
    }

    /**
     *
     */
    public function test_削除失敗()
    {   
        //削除する$idが見つからない

        $id = Todo::factory()->createOne()->id;

        $res = $this->deleteJson(route('api.todo.destroy', ['id' => $id + 1]));

        $res->assertStatus(404);
    }

    /**
     *
     */
    public function test_削除()
    {   
        //$idの削除処理をしたら消えていることが確認できるか

        $id = Todo::factory()->createOne()->id;

        $res = $this->deleteJson(route('api.todo.destroy', ['id' => $id]));

        $res->assertOk();

        $this->assertNull(Todo::find($id));
        
    }

    

}