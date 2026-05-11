<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_weak_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->enum('difficulty_level', ['moderate', 'difficult', 'very_difficult'])->default('moderate');
            $table->decimal('current_grade', 5, 2)->nullable();
            $table->boolean('needs_help')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_weak_subjects');
    }
};