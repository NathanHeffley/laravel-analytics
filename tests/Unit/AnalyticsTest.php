<?php

namespace NathanHeffley\Analytics\Test\Unit;

use Mockery;
use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Auth\User;
use NathanHeffley\Analytics\Analytics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \NathanHeffley\Analytics\Analytics */
    protected $analytics;

    protected function setUp()
    {
        parent::setUp();

        $this->analytics = new Analytics();
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
