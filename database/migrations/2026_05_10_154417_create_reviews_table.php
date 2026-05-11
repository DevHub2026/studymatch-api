<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutor_request_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->integer('communication_rating')->unsigned()->nullable();
            $table->integer('knowledge_rating')->unsigned()->nullable();
            $table->integer('punctuality_rating')->unsigned()->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            $table->unique('tutor_request_id');
            $table->index(['tutor_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};