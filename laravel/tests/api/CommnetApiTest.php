<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\StethoSound;
use App\Comment;

class CommnetApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $administrator;
    protected $supervisor;
    protected $other_supervisor;
    protected $stetho_sound;

    protected $baseurl;

    public function setUp() {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');

        $this->administrator = User::where('role', 99)->first();
        $this->supervisor    = User::where('role', 10)->first();
        $this->other_supervisor = factory(User::class)->create(['id' => 100, 'role' => 10]);
        $this->other_supervisor->save();
        $this->stetho_sound  = StethoSound::where('user_id', $this->supervisor->id)->first();

        $this->baseurl = '/admin/stetho_sounds/' . $this->stetho_sound->id . '/comments';
    }

    public function testシステム管理者はすべてのコメントを取得できること()
    {
        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->administrator->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        $content = $this->actingAs($this->administrator)
                        ->get($this->baseurl)
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals(3, count(json_decode($content)));
    }

    public function testシステム管理者はどの聴診音ライブラリにもコメントを投稿できること()
    {
        Session::start();
        $content = $this->actingAs($this->administrator)
                        ->post($this->baseurl, [
                            'text' => 'test',
                            '_token' => csrf_token()
                        ])->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals('test', json_decode($content)->text);
    }

    public function testシステム管理者は自分のコメントを編集できること()
    {
        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->administrator->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();

        $content = $this->actingAs($this->administrator)
                        ->put($this->baseurl . '/1', [
                            'text' => 'test',
                            '_token' => csrf_token()])
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals('test', json_decode($content)->text);
    }

    public function testシステム管理者は自分のコメントを削除できること()
    {
        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->administrator->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();
        
        $content = $this->actingAs($this->administrator)
                        ->delete($this->baseurl . '/1', ['_token' => csrf_token()])
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals(1, json_decode($content)->id);
    }

    public function test監修者は自分の担当するコメントを一覧できること()
    {
        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->supervisor->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();
        $content = $this->actingAs($this->supervisor)
                        ->get($this->baseurl)
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals(3, count(json_decode($content)));        
    }

    public function test監修者は自分の担当でないコメントを一覧できないこと()
    {
        $this->stetho_sound->user_id = $this->other_supervisor->id;
        $this->stetho_sound->save();
        $this->actingAs($this->supervisor)
             ->get($this->baseurl)
             ->seeStatusCode(404);
    }

    public function test監修者は自分の担当にコメントを投稿できること()
    {
        Session::start();
        $content = $this->actingAs($this->supervisor)
                        ->post($this->baseurl, [
                            'text' => 'test',
                            '_token' => csrf_token()
                        ])
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals('test', json_decode($content)->text);
    }

    public function test監修者は自分の担当以外にコメントできないこと()
    {
        Session::start();
        $this->stetho_sound->user_id = $this->other_supervisor->id;
        $this->stetho_sound->save();

        $content = $this->actingAs($this->supervisor)
                        ->post($this->baseurl, [
                            'text' => 'test',
                            '_token' => csrf_token()
                        ])
                        ->seeStatusCode(404);
    }

    public function test監修者は自分のコメントを更新できること()
    {
        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->supervisor->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();

        $content = $this->actingAs($this->supervisor)
                        ->put($this->baseurl . '/1', [
                            'text' => 'test',
                            '_token' => csrf_token()])
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals('test', json_decode($content)->text);
    }

    public function test監修者は自分以外のコメントを更新できないこと()
    {
        $this->stetho_sound->user_id = $this->other_supervisor->id;
        $this->stetho_sound->save();

        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->other_supervisor->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();

        $content = $this->actingAs($this->supervisor)
                        ->put($this->baseurl . '/1', [
                            'text' => 'test',
                            '_token' => csrf_token()])
                        ->seeStatusCode(404);
    }

    public function test監修者は自分のコメントを削除できること()
    {
        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->supervisor->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();
        
        $content = $this->actingAs($this->supervisor)
                        ->delete($this->baseurl . '/1', ['_token' => csrf_token()])
                        ->seeStatusCode(200)
                        ->response
                        ->getContent();
        $this->assertEquals(1, json_decode($content)->id);
    }

    public function test監修者は自分以外のコメントを削除できないこと()
    {
        $this->stetho_sound->user_id = $this->other_supervisor->id;
        $this->stetho_sound->save();

        $comments = factory(Comment::class, 3)->create([
            'stetho_sound_id' => $this->stetho_sound->id,
            'user_id' => $this->other_supervisor->id
        ])->each(function($c) {
            $this->stetho_sound->comments()->save($c);
        });

        Session::start();
        
        $content = $this->actingAs($this->supervisor)
                        ->delete($this->baseurl . '/1', ['_token' => csrf_token()])
                        ->seeStatusCode(404);
    }

    public function tearDown() {
        Artisan::call('clear-compiled');
        Artisan::call('cache:clear');     
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
