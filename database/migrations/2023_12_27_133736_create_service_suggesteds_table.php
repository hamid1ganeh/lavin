<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSuggestedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_suggesteds', function (Blueprint $table) {
            $table->unsignedBigInteger('number_id');
            $table->unsignedBigInteger('service_id');
            
            $table->foreign('number_id')->references('id')->on('numbers')
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
        Schema::dropIfExists('service_suggesteds');
    }
}
