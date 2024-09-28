<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;

class CreateGoodSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_id');
            $table->string('title');
            $table->unsignedSmallInteger('status')->default(Status::Active);
            $table->softDeletes();
            $table->timestamps();

            //references
            $table->foreign('main_id')
                ->references('id')
                ->on('goods_main_categories')
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
        Schema::dropIfExists('good_sub_categories');
    }
}
