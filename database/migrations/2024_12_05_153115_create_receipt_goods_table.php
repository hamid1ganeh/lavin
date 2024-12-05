<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('good_id');
            $table->unsignedDouble('count');
            $table->unsignedDouble('unit_cost');
            $table->unsignedDouble('total_cost');
            $table->timestamps();

            $table->foreign('receipt_id')
                ->references('id')
                ->on('warehouse_receipts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('good_id')
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
        Schema::dropIfExists('receipt_goods');
    }
}
