<?php

namespace NathanHeffley\Analytics\Test\Unit\Http\Middleware;

use Illuminate\Foundation\Auth\User;
use Mockery;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use NathanHeffley\Analytics\Http\Middleware\Pageview;

class PageviewTest extends TestCase
{
    use RefreshDatabase;

    /** @var \NathanHeffley\Analytics\Http\Middleware\Pageview $middleware */
    protected $middleware;

    protected function setUp()
    {
        parent::setUp();

        $this->middleware = new Pageview();
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    protected function getPackageProviders($app)
    {
        return [
            'NathanHeffley\Analytics\AnalyticsServiceProvider',
        ];
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
