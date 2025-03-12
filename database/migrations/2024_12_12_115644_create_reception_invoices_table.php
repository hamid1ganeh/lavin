<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceptionInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reception_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reception_id');
            $table->string('number',255);
            $table->unsignedBigInteger('sum_price');
            $table->unsignedBigInteger('sum_discount_price')->default(0);
            $table->unsignedBigInteger('sum_upgrades_price')->default(0);
            $table->unsignedBigInteger('final_price');
            $table->unsignedBigInteger('amount_paid')->default(0);
            $table->unsignedBigInteger('amount_debt')->default(0);
            $table->timestamps();

            $table->foreign('reception_id')
                ->references('id')
                ->on('receptions')
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
