<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReserveConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve_consumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserve_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('goods_id');
            $table->string('unit');
            $table->unsignedBigInteger('value')->default(0);
            $table->unsignedBigInteger('price_per_unit')->default(0);
            $table->unsignedBigInteger('total_price')->default(0);
            $table->timestamps();

            //references
            $table->foreign('reserve_id')
                ->references('id')
                ->on('service_reserves')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('goods_id')
                ->references('id')
                ->on('goods')
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
        Schema::dropIfExists('reserve_consumptions');
    }
}
