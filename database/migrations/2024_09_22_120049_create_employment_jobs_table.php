<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;

class CreateEmploymentJobsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employment_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_cat_id');
            $table->unsignedBigInteger('sub_cat_id')->nullable();
            $table->string('title');
            $table->unsignedSmallInteger('status')->default(Status::Active);
            $table->softDeletes();
            $table->timestamps();

            //references
            $table->foreign('main_cat_id')
                ->references('id')
                ->on('employment_main_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('sub_cat_id')
                ->references('id')
                ->on('employment_sub_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_jobs');
    }
};
