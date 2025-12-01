<?php
// database/migrations/2025_10_01_000001_create_applications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('nationality');
            $table->string('nrc_number');
            $table->text('address');
            $table->enum('application_type', ['new', 'old'])->default('new');
            $table->string('department');
            
            // Educational background
            $table->string('high_school_name');
            $table->text('high_school_address');
            $table->integer('graduation_year');
            $table->decimal('matriculation_score', 5, 2);
            $table->string('previous_qualification');
            
            // Payment and status fields
            $table->string('payment_status')->default('pending');
            $table->string('payment_verified_by')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            
            // Academic approval
            $table->string('academic_approved_by')->nullable();
            $table->timestamp('academic_approved_at')->nullable();
            
            // Final approval
            $table->string('final_approved_by')->nullable();
            $table->timestamp('final_approved_at')->nullable();
            
            // Application status and metadata
            $table->string('status', 50)->default('payment_pending');
            $table->string('student_id')->nullable();
            $table->integer('current_year')->nullable();
            $table->string('application_purpose')->nullable();
            $table->text('reason_for_application')->nullable();
            $table->timestamp('application_date')->useCurrent();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('email');
            $table->index('application_type');
            $table->index('department');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};