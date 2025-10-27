<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * /test1 ページの表示テスト。
 */
class Test1PageTest extends TestCase
{
    /** @test */
    public function test_test1_page_is_accessible_and_shows_expected_text(): void
    {
        $response = $this->get('/test1');

        $response->assertStatus(200);
        $response->assertSee('/test1 テストページ');
        $response->assertSee('これは Bootstrap 5 が有効なテストページです。');
    }
}
