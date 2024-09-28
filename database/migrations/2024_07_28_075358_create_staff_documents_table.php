<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('personal_stats',1)->default('0');
            $table->string('personal_desc')->nullable();
            $table->string('socialmedia_status',1)->default('0');
            $table->string('socialmedia_desc')->nullable();
            $table->string('education_status',1)->default('0');
            $table->string('education_desc')->nullable();
            $table->string('bank_status',1)->default('0');
            $table->string('bank_confirmed_desc')->nullable();
            $table->string('retraining_status',1)->default('0');
            $table->string('retraining_desc')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_documents');
    }
}
