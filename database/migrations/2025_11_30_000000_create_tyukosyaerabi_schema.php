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
        // te_users
        Schema::create('te_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('email', 191)->nullable()->unique();
            $table->string('password', 191)->nullable();
            $table->string('visitor_id', 100)->nullable()->comment('匿名ユーザー識別ID');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE te_users COMMENT 'tyukosyaerabi users'");

        // te_profiles
        Schema::create('te_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('te_user_id')->constrained()->onDelete('cascade');
            $table->smallInteger('height_cm')->nullable();
            $table->smallInteger('weight_kg')->nullable();
            $table->smallInteger('inseam_cm')->nullable();
            $table->decimal('experience_years', 3, 1)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('license')->nullable();
            $table->json('preferences')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE te_profiles COMMENT 'tyukosyaerabi user profiles'");


        // te_genres
        Schema::create('te_genres', function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        // te_questions
        Schema::create('te_questions', function (Blueprint $table) {
            $table->id('id');
            $table->smallInteger('ono')->comment('表示順番');
            $table->string('section', 50);
            $table->string('body', 255);
            $table->enum('answer_type', ['single', 'multi'])->default('single');
            $table->timestamps();
        });

        // te_options
        Schema::create('te_options', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('sno')->comment('質問内連番');
            $table->foreignId('question_id')->constrained('te_questions', 'id')->onDelete('cascade');
            $table->string('label', 100);
            $table->timestamps();
        });

        // te_weights
        Schema::create('te_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('te_questions')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('te_options', 'id')->onDelete('cascade');
            $table->unsignedBigInteger('genre_id');
            $table->unsignedInteger('score');
            $table->unique(['question_id', 'option_id', 'genre_id'], 'uq_weight');
            $table->foreign('genre_id')->references('id')->on('te_genres')->onDelete('cascade');
            $table->timestamps();
        });

        // te_diagnoses
        Schema::create('te_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('te_user_id')->nullable()->constrained('te_users')->nullOnDelete();
            $table->json('summary')->nullable(); // 上位3・レーダー配列
            $table->timestamps();
        });

        // te_answers
        Schema::create('te_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('te_user_id')->nullable()->constrained('te_users')->nullOnDelete();
            $table->foreignId('te_diagnosis_id')->constrained('te_diagnoses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('te_questions')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('te_options', 'id')->onDelete('cascade');
            $table->timestamps();
        });

        // te_diagnosis_scores
        Schema::create('te_diagnosis_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('te_diagnoses')->onDelete('cascade');
            $table->unsignedBigInteger('genre_id');
            $table->unsignedInteger('score');
            $table->unsignedInteger('rank')->nullable();
            $table->unique(['diagnosis_id', 'genre_id'], 'uq_diag_genre');
            $table->foreign('genre_id')->references('id')->on('te_genres')->onDelete('cascade');
            $table->timestamps();
        });

        // te_recommendations
        Schema::create('te_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('genre_id');
            $table->enum('type', ['入門講座', '安全講座', '整備講座', '記事', '動画', 'イベント']);
            $table->string('title', 200);
            $table->string('url', 500)->nullable();
            $table->string('region', 100)->nullable();
            $table->json('meta')->nullable();
            $table->foreign('genre_id')->references('id')->on('te_genres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('te_recommendations');
        Schema::dropIfExists('te_diagnosis_scores');
        Schema::dropIfExists('te_answers');
        Schema::dropIfExists('te_diagnoses');
        Schema::dropIfExists('te_weights');
        Schema::dropIfExists('te_options');
        Schema::dropIfExists('te_questions');
        Schema::dropIfExists('te_genres');
        Schema::dropIfExists('te_profiles');
        Schema::dropIfExists('te_users');
    }
};
