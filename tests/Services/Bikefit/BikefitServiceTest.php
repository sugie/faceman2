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

}
