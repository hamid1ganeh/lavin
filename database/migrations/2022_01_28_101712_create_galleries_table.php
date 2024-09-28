<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->tinyInteger('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('gallery_service_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('gallery_id');
            $table->unsignedBigInteger('service_detail_id');

            $table->foreign('gallery_id')->references('id')->on('galleries')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('service_detail_id')->references('id')->on('service_details')
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
        Schema::dropIfExists('images_galleries');
    }
}
