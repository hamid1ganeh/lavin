<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyseImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyse_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('analyse_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('required')->default(1);
            $table->timestamps();

            $table->foreign('analyse_id')->references('id')->on('analyses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analyse_images');
    }
}
