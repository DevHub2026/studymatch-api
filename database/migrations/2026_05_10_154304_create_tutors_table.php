<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('tutors', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('employee_id', 50)->unique()->nullable();
        $table->string('position')->nullable();
        $table->enum('tutor_type', ['professor', 'instructor', 'student_tutor'])->default('professor');
        $table->text('specialization')->nullable();
        $table->decimal('hourly_rate', 10, 2)->nullable();
        $table->text('bio')->nullable();
        $table->text('credentials')->nullable();
        $table->json('verification_documents')->nullable();
        $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamp('verified_at')->nullable();
        $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
        $table->decimal('average_rating', 3, 2)->default(0.00);
        $table->integer('total_sessions')->default(0);
        $table->decimal('total_earnings', 12, 2)->default(0.00);
        $table->boolean('is_available')->default(true);
        $table->boolean('is_deans_list')->default(false);
        $table->decimal('gpa', 4, 2)->nullable();
        $table->timestamps();
        
        $table->index('verification_status');
        $table->index('average_rating');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
