<?php

namespace Tests\Services\Bikefit;

class BikefitServiceTest extends \Tests\TestCase
{
    public function test_getBestOne()
    {
        $genre_scores = [
            1 => 10,
            2 => 25,
            3 => 15,
        ];

        $best_genre = \App\Services\Bikefit\BikefitService::getBestOne($genre_scores);
        $this->assertSame(2, $best_genre);

        // Test with tie scores
        $genre_scores = [
            1 => 30,
            2 => 30,
            3 => 20,
        ];

        $best_genre = \App\Services\Bikefit\BikefitService::getBestOne($genre_scores);
        $this->assertContains($best_genre, [1, 2]);
    }

    public function test_getBestOne_withEmptyScores()
    {
        $genre_scores = ["1" => 0, "2" => 4, "3" => 0, "4" => 0, "5" => 0, "6" => 4, "7" => 4, "8" => 4, "9" => 0, "10" => 0, "11" => 4, "12" => 0, "13" => 0, "14" => 0, "15" => 0];

        $best_one = \App\Services\Bikefit\BikefitService::getBestOne($genre_scores);
        $v = $best_one == 2;
        $this->assertTrue($best_one == 2);
        $this->assertSame($best_one, 2);
    }

}
