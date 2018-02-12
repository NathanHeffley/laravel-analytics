<?php

namespace NathanHeffley\Analytics\Tests\Unit;

use Mockery;
use Illuminate\Foundation\Auth\User;
use NathanHeffley\Analytics\Analytics;
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

    /** @test */
    public function analytics_can_record_a_pageview_for_a_guest()
    {
        $this->analytics->record('pageview', [
            'path' => 'example/page',
        ]);

        $this->assertDatabaseHas('pageviews', [
            'user_id' => null,
            'path' => 'example/page',
        ]);
    }

    /** @test */
    public function analytics_can_record_a_pageview_for_a_given_user()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $this->analytics->record('pageview', [
            'user' => $user,
            'path' => 'example/page',
        ]);

        $this->assertDatabaseHas('pageviews', [
            'user_id' => 1,
            'path' => 'example/page',
        ]);
    }
}
