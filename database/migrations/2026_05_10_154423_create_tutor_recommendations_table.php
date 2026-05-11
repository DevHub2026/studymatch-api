<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('match_score', 5, 2);
            $table->json('match_reasons')->nullable();
            $table->enum('recommendation_level', ['low', 'medium', 'high', 'excellent'])->default('medium');
            $table->boolean('is_viewed')->default(false);
            $table->boolean('is_contacted')->default(false);
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'match_score']);
            $table->index('recommendation_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_recommendations');
    }
};