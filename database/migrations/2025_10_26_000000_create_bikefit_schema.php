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
        // bf_users
        Schema::create('bf_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('email', 191)->unique();
            $table->string('password', 191);
            $table->timestamps();
        });
        DB::statement("ALTER TABLE bf_users COMMENT 'Bike fit users'");

        // bf_profiles
        Schema::create('bf_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bf_user_id')->constrained()->onDelete('cascade');
            $table->smallInteger('height_cm')->nullable();
            $table->smallInteger('weight_kg')->nullable();
            $table->smallInteger('inseam_cm')->nullable();
            $table->decimal('experience_years', 3, 1)->nullable();
            $table->string('region', 100)->nullable();
            $table->enum('license', ['原付', '小型限定', '普通二輪', '大型'])->nullable();
            $table->json('preferences')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE bf_profiles COMMENT 'Bike fit user profiles'");


        // bf_genres
        Schema::create('bf_genres', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name')->unique();
        });

        // bf_questions
        Schema::create('bf_questions', function (Blueprint $table) {
            $table->id('id');
            $table->string('section', 50);
            $table->string('body', 255);
            $table->enum('answer_type', ['single', 'multi'])->default('single');
        });

        // bf_options
        Schema::create('bf_options', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('sno')->comment('質問内連番');
            $table->foreignId('question_id')->constrained('bf_questions', 'id')->onDelete('cascade');
            $table->string('label', 100);
        });

        // bf_weights
        Schema::create('bf_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('bf_questions')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('bf_options', 'id')->onDelete('cascade');
            $table->unsignedTinyInteger('genre_id');
            $table->unsignedTinyInteger('score');
            $table->unique(['question_id', 'option_id', 'genre_id'], 'uq_weight');
            $table->foreign('genre_id')->references('id')->on('bf_genres')->onDelete('cascade');
        });

        // bf_diagnoses
        Schema::create('bf_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bf_user_id')->nullable()->constrained('bf_users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->json('summary')->nullable(); // 上位3・レーダー配列
        });

        // bf_answers
        Schema::create('bf_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('bf_diagnoses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('bf_questions')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('bf_options', 'id')->onDelete('cascade');
        });

        // bf_diagnosis_scores
        Schema::create('bf_diagnosis_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('bf_diagnoses')->onDelete('cascade');
            $table->unsignedTinyInteger('genre_id');
            $table->unsignedSmallInteger('score');
            $table->unsignedTinyInteger('rank')->nullable();
            $table->unique(['diagnosis_id', 'genre_id'], 'uq_diag_genre');
            $table->foreign('genre_id')->references('id')->on('bf_genres')->onDelete('cascade');
        });

        // bf_recommendations
        Schema::create('bf_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('genre_id');
            $table->enum('type', ['入門講座', '安全講座', '整備講座', '記事', '動画', 'イベント']);
            $table->string('title', 200);
            $table->string('url', 500)->nullable();
            $table->string('region', 100)->nullable();
            $table->json('meta')->nullable();
            $table->foreign('genre_id')->references('id')->on('bf_genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bf_recommendations');
        Schema::dropIfExists('bf_diagnosis_scores');
        Schema::dropIfExists('bf_answers');
        Schema::dropIfExists('bf_diagnoses');
        Schema::dropIfExists('bf_weights');
        Schema::dropIfExists('bf_options');
        Schema::dropIfExists('bf_questions');
        Schema::dropIfExists('bf_genres');
        Schema::dropIfExists('bf_profiles');
        Schema::dropIfExists('bf_users');
    }
};
