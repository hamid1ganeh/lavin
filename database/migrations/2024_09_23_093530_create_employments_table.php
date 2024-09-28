<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EmploymentStatus;

class CreateEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('mobile');
            $table->unsignedBigInteger('job_id');
            $table->string('about')->nullable();
            $table->string('result')->nullable();
            $table->string('resume');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->date('startEducation')->nullable();
            $table->date('endEducation')->nullable();
            $table->unsignedSmallInteger('status')->default(EmploymentStatus::pending);
            $table->timestamps();

            //references
            $table->foreign('job_id')
                ->references('id')
                ->on('employment_jobs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            //references
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employments');
    }
};
