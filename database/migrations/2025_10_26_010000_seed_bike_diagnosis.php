<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('bf_questions')->insert([
            ['id' => 1, 'section' => '体格', 'question' => '身長のレンジを選んでください', 'answer_type' => 'single'],
            ['id' => 2, 'section' => '体格', 'question' => '体重のレンジを選んでください', 'answer_type' => 'single'],
            ['id' => 3, 'section' => '体格', 'question' => '足つき性（足がべったり着くこと）の重視度', 'answer_type' => 'single'],
            ['id' => 4, 'section' => '経験', 'question' => 'バイクの運転経験年数', 'answer_type' => 'single'],
            ['id' => 5, 'section' => '用途', 'question' => '主な用途（最も近いもの1つ）', 'answer_type' => 'single'],
            ['id' => 6, 'section' => '環境', 'question' => '主な走行環境（最も近いもの1つ）', 'answer_type' => 'single'],
            ['id' => 7, 'section' => '嗜好', 'question' => '取り回しの軽さ重視度', 'answer_type' => 'single'],
            ['id' => 8, 'section' => '嗜好', 'question' => 'デザイン志向に最も近いもの', 'answer_type' => 'single'],
            ['id' => 9, 'section' => 'コスト', 'question' => '維持費の重視度', 'answer_type' => 'single'],
            ['id' => 10, 'section' => '快適', 'question' => '積載・快適性の重視度', 'answer_type' => 'single'],
            ['id' => 11, 'section' => '制約', 'question' => '購入予算（万円）', 'answer_type' => 'single'],
            ['id' => 12, 'section' => '免許', 'question' => '現在の二輪免許区分', 'answer_type' => 'single'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
