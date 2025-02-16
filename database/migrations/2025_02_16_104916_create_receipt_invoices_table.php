<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->string('number',255);
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount_price')->default(0);
            $table->string('description')->nullable();
            $table->unsignedBigInteger('final_price');
            $table->boolean('settlement')->default(false);
            $table->timestamps();

            $table->foreign('receipt_id')
                ->references('id')
                ->on('warehouse_receipts')
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
        Schema::dropIfExists('receipt_invoices');
    }
}
