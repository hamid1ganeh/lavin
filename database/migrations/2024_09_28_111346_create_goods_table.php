<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Unit;
use App\Enums\Status;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('brand');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('main_cat_id');
            $table->unsignedBigInteger('sub_cat_id')->nullable();
            $table->string('unit')->default(Unit::cc);
            $table->unsignedDouble('value_per_count')->default(0);
            $table->unsignedDouble('count_stock')->default(0);
            $table->unsignedDouble('unit_stock')->default(0);
            $table->unsignedBigInteger('price')->default(0);
            $table->string('description')->nullable();
            $table->date('expire_date')->nullable();
            $table->unsignedSmallInteger('status')->default(Status::Active);
            $table->softDeletes();
            $table->timestamps();

            //references
            $table->foreign('main_cat_id')
                ->references('id')
                ->on('goods_main_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('sub_cat_id')
                ->references('id')
                ->on('goods_sub_categories')
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
        Schema::dropIfExists('goods');
    }
}
