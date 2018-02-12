<?php

namespace NathanHeffley\Analytics\Tests\Unit\Http\Middleware;

use Mockery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use NathanHeffley\Analytics\Tests\UnitTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use NathanHeffley\Analytics\Http\Middleware\Pageview;

class PageviewTest extends UnitTest
{
    use RefreshDatabase;

    /** @var \NathanHeffley\Analytics\Http\Middleware\Pageview $middleware */
    protected $middleware;

    protected function setUp()
    {
        parent::setUp();

        $this->middleware = new Pageview();
    }

    /** @test */
    public function pageview_middleware_handles_guest_users()
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('path')->once()->andReturn('example/path');

        $this->middleware->handle($request, function ($request) {});

        $this->assertDatabaseHas('pageviews', [
            'user_id' => null,
            'path' => 'example/path',
        ]);
    }

    /** @test */
    public function pageview_middleware_handles_logged_in_users()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);

        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('path')->once()->andReturn('example/path');

        $this->middleware->handle($request, function ($request) {});

        $this->assertDatabaseHas('pageviews', [
            'user_id' => 1,
            'path' => 'example/path',
        ]);
    }
}
