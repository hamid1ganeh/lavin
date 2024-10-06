<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;
class CreateWarehousesTable extends Migration
{

    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('status')->default(Status::Active);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('admin_warehouse', function (Blueprint $table) {

            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->foreign('admin_id')->references('id')->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['admin_id','warehouse_id']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
