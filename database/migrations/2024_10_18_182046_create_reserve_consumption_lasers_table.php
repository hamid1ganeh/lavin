<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReserveConsumptionLasersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve_consumption_lasers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserve_id');
            $table->unsignedBigInteger('service_laser_id');
            $table->unsignedBigInteger('laser_device_id');
            $table->unsignedBigInteger('recent_shot_number');
            $table->unsignedBigInteger('shot_number');
            $table->unsignedBigInteger('shot');
            $table->dateTime('started_at');
            $table->dateTime('finished_at');
            $table->timestamps();

            //references
            $table->foreign('service_laser_id')
                ->references('id')
                ->on('service_lasers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('reserve_id')
                ->references('id')
                ->on('service_reserves')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('laser_device_id')
                ->references('id')
                ->on('laser_devices')
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
        Schema::dropIfExists('reserve_consumption_lasers');
    }
}
