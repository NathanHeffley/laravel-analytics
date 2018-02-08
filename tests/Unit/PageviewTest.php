<?php

namespace NathanHeffley\Analytics\Test\Unit;

use Mockery;
use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Auth\User;
use NathanHeffley\Analytics\Pageview;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageviewTest extends TestCase
{
    use RefreshDatabase;

    /** @var \NathanHeffley\Analytics\Pageview */
    protected $pageview;

    protected function setUp()
    {
        parent::setUp();

        $this->pageview = new Pageview();
    }

    protected function getPackageProviders($app)
    {
        return [
            'NathanHeffley\Analytics\AnalyticsServiceProvider',
        ];
    }

    /** @test */
    public function pageview_can_record_a_view_for_a_guest()
    {
        $this->pageview->record('/example-page');

        $this->assertDatabaseHas('pageviews', [
            'user_id' => null,
            'path' => '/example-page',
        ]);
    }

    /** @test */
    public function pageview_can_record_a_view_for_a_user()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $this->pageview->record('/example-page', $user);

        $this->assertDatabaseHas('pageviews', [
            'user_id' => 1,
            'path' => '/example-page',
        ]);
    }
}
