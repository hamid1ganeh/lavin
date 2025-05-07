<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Unit;

class CreateWarehouseStockHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('moved_warehouse_id')->nullable();
            $table->unsignedBigInteger('goods_id');
            $table->char('event',1);
            $table->unsignedDouble('stock')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('moved_warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('goods_id')
                ->references('id')
                ->on('goods')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('delivered_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_stock_histories');
    }
}
