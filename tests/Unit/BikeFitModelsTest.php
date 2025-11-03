<?php

namespace Tests\Unit;

use App\Models\BikeFit\{BfUser, BfProfile, BfGenre, BfQuestion, BfOption, BfWeight, BfDiagnosis, BfAnswer, BfDiagnosisScore, BfRecommendation};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Tests\TestCase;

class BikeFitModelsTest extends TestCase
{
    public function test_tables_and_basic_config()
    {
        $this->assertSame('bf_users', (new BfUser())->getTable());
        $this->assertSame('bf_profiles', (new BfProfile())->getTable());
        $this->assertSame('bf_genres', (new BfGenre())->getTable());
        $this->assertSame('bf_questions', (new BfQuestion())->getTable());
        $this->assertSame('bf_options', (new BfOption())->getTable());
        $this->assertSame('bf_weights', (new BfWeight())->getTable());
        $this->assertSame('bf_diagnoses', (new BfDiagnosis())->getTable());
        $this->assertSame('bf_answers', (new BfAnswer())->getTable());
        $this->assertSame('bf_diagnosis_scores', (new BfDiagnosisScore())->getTable());
        $this->assertSame('bf_recommendations', (new BfRecommendation())->getTable());
    }

    public function test_casts()
    {
        $profile = new BfProfile();
        $this->assertArrayHasKey('preferences', $profile->getCasts());
        $this->assertSame('array', $profile->getCasts()['preferences']);

        $diag = new BfDiagnosis();
        $this->assertArrayHasKey('summary', $diag->getCasts());
        $this->assertSame('array', $diag->getCasts()['summary']);

        $rec = new BfRecommendation();
        $this->assertArrayHasKey('meta', $rec->getCasts());
        $this->assertSame('array', $rec->getCasts()['meta']);
    }

    public function test_relationship_types()
    {
        $this->assertInstanceOf(HasMany::class, (new BfUser())->profiles());
        $this->assertInstanceOf(HasMany::class, (new BfUser())->diagnoses());

        $this->assertInstanceOf(BelongsTo::class, (new BfProfile())->user());

        $this->assertInstanceOf(HasMany::class, (new BfGenre())->weights());
        $this->assertInstanceOf(HasMany::class, (new BfGenre())->diagnosisScores());
        $this->assertInstanceOf(HasMany::class, (new BfGenre())->recommendations());

        $this->assertInstanceOf(HasMany::class, (new BfQuestion())->options());
        $this->assertInstanceOf(HasMany::class, (new BfQuestion())->weights());
        $this->assertInstanceOf(HasMany::class, (new BfQuestion())->answers());

        $this->assertInstanceOf(BelongsTo::class, (new BfOption())->question());
        $this->assertInstanceOf(HasMany::class, (new BfOption())->weights());
        $this->assertInstanceOf(HasMany::class, (new BfOption())->answers());

        $this->assertInstanceOf(BelongsTo::class, (new BfWeight())->question());
        $this->assertInstanceOf(BelongsTo::class, (new BfWeight())->option());
        $this->assertInstanceOf(BelongsTo::class, (new BfWeight())->genre());

        $this->assertInstanceOf(BelongsTo::class, (new BfAnswer())->diagnosis());
        $this->assertInstanceOf(BelongsTo::class, (new BfAnswer())->question());
        $this->assertInstanceOf(BelongsTo::class, (new BfAnswer())->option());

        $this->assertInstanceOf(BelongsTo::class, (new BfDiagnosisScore())->diagnosis());
        $this->assertInstanceOf(BelongsTo::class, (new BfDiagnosisScore())->genre());

        $this->assertInstanceOf(BelongsTo::class, (new BfRecommendation())->genre());
    }
}
