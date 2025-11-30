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
        // docs/bike_diagnosis_questionnaire.xlsx code 5313088969
        DB::table('te_questions')->insert([


            ['id' => 100, 'ono' => 100, 'section' => '基本情報', 'body' => '希望メーカー', 'answer_type' => 'single'],
        ]);
        // End of docs/bike_diagnosis_questionnaire.xlsx code 5313088969


        // docs/bike_diagnosis_questionnaire.xlsx code 1646515591
        DB::table('te_options')->insert([
            ['id' => '1001', 'sno' => '1', 'question_id' => 100, 'label' => 'トヨタ',],
            ['id' => '1002', 'sno' => '2', 'question_id' => 100, 'label' => '日産',],
            ['id' => '1003', 'sno' => '3', 'question_id' => 100, 'label' => 'ホンダ',],
            ['id' => '1004', 'sno' => '4', 'question_id' => 100, 'label' => 'マツダ',],
        ]);
        // End of docs/bike_diagnosis_questionnaire.xlsx code 1646515591


        // docs/bike_diagnosis_questionnaire.xlsx code 3993290321
        DB::table('te_genres')->insert([
            ['id' => 9010, 'name' => '軽自動車',],
            ['id' => 9020, 'name' => 'スポーツカー',],
            ['id' => 9030, 'name' => 'SUV',],
            ['id' => 9040, 'name' => 'コンパクトカー',],
            ['id' => 9050, 'name' => 'ワンボックス',],
            ['id' => 9060, 'name' => 'セダン',],
            ['id' => 9070, 'name' => 'ワゴン',],
            ['id' => 9080, 'name' => '作業車',],
            ['id' => 9090, 'name' => 'その他',],
        ]);
        // End of docs/bike_diagnosis_questionnaire.xlsx code 3993290321

        DB::table('te_weights')->insert([


            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9010, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9020, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9030, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9040, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9050, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9060, 'score' => 5],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9070, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9080, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1001, 'genre_id' => 9090, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9010, 'score' => 5],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9020, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9030, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9040, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9050, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9060, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9070, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9080, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1002, 'genre_id' => 9090, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9010, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9020, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9030, 'score' => 5],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9040, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9050, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9060, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9070, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9080, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1003, 'genre_id' => 9090, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9010, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9020, 'score' => 5],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9030, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9040, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9050, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9060, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9070, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9080, 'score' => 0],
            ['question_id' => 100, 'option_id' => 1004, 'genre_id' => 9090, 'score' => 0],
        ]);
        // End of docs/bike_diagnosis_questionnaire.xlsx code 3230580247


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
