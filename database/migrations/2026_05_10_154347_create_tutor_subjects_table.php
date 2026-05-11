<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->enum('expertise_level', ['competent', 'proficient', 'expert', 'master'])->default('competent');
            $table->decimal('grade_achieved', 5, 2)->nullable();
            $table->integer('years_teaching')->default(0);
            $table->boolean('is_primary_expertise')->default(false);
            $table->timestamps();
            
            $table->unique(['tutor_id', 'subject_id']);
            $table->index('expertise_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_subjects');
    }
};