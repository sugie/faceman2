<?php

namespace Tests\Unit;

use App\Models\BikeFit\BfWeight;
use PHPUnit\Framework\TestCase;

class BfWeightTest extends TestCase
{
    public function tearDown(): void
    {
        // Reset test weights after each test
        BfWeight::resetTestWeights();
        parent::tearDown();
    }

    public function test_getDiagnostic_with_test_weights_and_answers()
    {
        // Prepare test answers: two answers, question 1 option 1, question 2 option 3
        $answers = [
            (object)['question_id' => 1, 'option_id' => 1],
            (object)['question_id' => 2, 'option_id' => 3],
        ];

        // Prepare test weights keyed by "question:option". Each entry is an array of weight rows.
        $map = [
            '1:1' => [
                ['genre_id' => 1, 'score' => 5],
                ['genre_id' => 2, 'score' => 2],
            ],
            '2:3' => [
                ['genre_id' => 1, 'score' => 1],
                ['genre_id' => 3, 'score' => 4],
            ],
        ];

        BfWeight::setTestWeights($map);
        $result = BfWeight::getDiagnostic(12345, $answers);

        // Expected totals: genre 1 => 5 + 1 = 6, genre 2 => 2, genre 3 => 4
        $this->assertIsArray($result);
        $this->assertSame(6, $result[1]);
        $this->assertSame(2, $result[2]);
        $this->assertSame(4, $result[3]);
    }
}

