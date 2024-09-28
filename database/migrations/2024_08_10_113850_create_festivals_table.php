<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festivals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('end')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->boolean('display')->default(0);
            $table->timestamps();
        });

        Schema::create('discount_festival', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id');
            $table->unsignedBigInteger('festival_id');

            $table->foreign('discount_id')->references('id')->on('discounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('festival_id')->references('id')->on('festivals')
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
        Schema::dropIfExists('festivals');
    }
}
