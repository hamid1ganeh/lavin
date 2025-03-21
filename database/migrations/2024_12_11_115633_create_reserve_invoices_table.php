<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReserveInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserve_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reception_id');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('discount_price')->default(0);
            $table->string('discount_description')->nullable();
            $table->unsignedBigInteger('sum_upgrades_price')->default(0);
            $table->unsignedBigInteger('final_price');
            $table->timestamps();

            $table->foreign('reception_id')
                ->references('id')
                ->on('receptions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('discount_id')
                ->references('id')
                ->on('discounts')
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
        Schema::dropIfExists('reserve_invoices');
    }
}
