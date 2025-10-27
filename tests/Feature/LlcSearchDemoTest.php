<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * /llc/search デモ固定表示のテスト（zip=1920373 のとき）
 */
class LlcSearchDemoTest extends TestCase
{
    /** @test */
    public function it_shows_static_demo_for_specific_zip(): void
    {
        $response = $this->get('/llc/search?zip=1920373');

        $response->assertStatus(200);
        $response->assertSee('郵便番号 1920373');
        $response->assertSee('東京都八王子市上柚木');
        $response->assertSee('生涯学習センター一覧');
        $response->assertSee('　１．生涯学習センター(クリエイトホール）');
    }
}
