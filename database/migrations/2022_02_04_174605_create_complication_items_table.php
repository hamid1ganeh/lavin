<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplicationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complication_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserve_id');
            $table->string('prescription')->nullable();
            $table->text('explain')->nullable();
            $table->string('status',0)->nullable();
            $table->timestamps();

            $table->foreign('reserve_id')->references('id')->on('service_reserves')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('complication_complication_item', function (Blueprint $table) {

            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('complication_id');

            $table->foreign('item_id')->references('id')->on('complication_items')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('complication_id')->references('id')->on('complications')
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
        Schema::dropIfExists('complication_items');
    }
}
