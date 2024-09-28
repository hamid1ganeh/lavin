<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('highlight_id')->nullable();
            $table->string('title');
            $table->text('link')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->foreign('highlight_id')->references('id')->on('highlights')
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
        Schema::dropIfExists('stories');
    }
}
