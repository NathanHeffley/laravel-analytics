<?php

namespace NathanHeffley\Analytics\Tests\Unit;

use Mockery;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Config;
use NathanHeffley\Analytics\Analytics;
use NathanHeffley\Analytics\Pageview;
use NathanHeffley\Analytics\Tests\UnitTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyticsTest extends UnitTest
{
    use RefreshDatabase;

    /** @var \NathanHeffley\Analytics\Analytics */
    protected $analytics;

    protected function setUp()
    {
        parent::setUp();

        $this->analytics = new Analytics();
    }

    /**
     * @test
     * @throws
    */
    public function analytics_can_record_a_pageview_for_a_guest()
    {
        $this->analytics->record('pageview', [
            'path' => 'example/page',
        ]);

        $this->assertEquals(1, Pageview::count());
        $this->assertDatabaseHas('pageviews', [
            'user_id' => null,
            'path' => 'example/page',
        ]);
    }

    /**
     * @test
     * @throws
    */
    public function analytics_can_record_a_pageview_for_a_given_user()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $this->analytics->record('pageview', [
            'user' => $user,
            'path' => 'example/page',
        ]);

        $this->assertEquals(1, Pageview::count());
        $this->assertDatabaseHas('pageviews', [
            'user_id' => 1,
            'path' => 'example/page',
        ]);
    }

    /** @test */
    public function analytics_does_not_record_a_pageview_for_an_excluded_user()
    {
        Config::set('excluded', [
            'column' => 'role',
            'values' => [
                'manager',
                'admin',
            ],
        ]);

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->with('role')->andReturn('admin');

        $this->analytics->record('pageview', [
            'user' => $user,
            'path' => 'example/page',
        ]);

        $this->assertDatabaseMissing('pageviews', [
            'user_id' => 1,
            'path' => 'example/page',
        ]);
    }
}
