<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaserTubeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laser_tube_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laser_device_id');
            $table->unsignedBigInteger('goods_id');
            $table->string('good_title');
            $table->string('good_brand');
            $table->unsignedBigInteger('changed_by');
            $table->unsignedBigInteger('shot');
            $table->unsignedBigInteger('waste');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('laser_device_id')
                ->references('id')
                ->on('laser_devices')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('goods_id')
                ->references('id')
                ->on('goods')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('changed_by')
                ->references('id')
                ->on('admins')
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
        Schema::dropIfExists('laser_tube_histories');
    }
}
