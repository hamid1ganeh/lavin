<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdviserServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adviser_service', function (Blueprint $table) {
            $table->unsignedBigInteger('adviser_id');
            $table->unsignedBigInteger('service_id');
            
            $table->foreign('adviser_id')->references('id')->on('admins')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('service_id')->references('id')->on('service_details')
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
        Schema::dropIfExists('adviser_service');
    }
}
