<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ReceiptType;

class CreateWarehouseReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default(ReceiptType::received);
            $table->string('number');
            $table->string('seller')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('exporter_id')->nullable();
            $table->unsignedDouble('price')->default(0);
            $table->unsignedDouble('discount')->default(0);
            $table->unsignedDouble('total_cost')->default(0);
            $table->timestamps();

            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('exporter_id')
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
        Schema::dropIfExists('warehouse_receipts');
    }
}
