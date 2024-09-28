<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_reserves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->unsignedBigInteger('detail_id');
            $table->string('detail_name');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('secratry_id')->nullable();
            $table->unsignedBigInteger('asistant_id')->nullable();
            $table->unsignedBigInteger('adviser_id')->nullable();
            $table->unsignedBigInteger('reception_id')->nullable();
            $table->unsignedBigInteger('complication_id')->nullable();
            $table->DateTime('time')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('type',1)->nullable();
            $table->DateTime('doneTime')->nullable();
            $table->text('reception_desc')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('reception_id')->references('id')->on('receptions')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('branch_id')->references('id')->on('branchs')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('service_id')->references('id')->on('services')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('detail_id')->references('id')->on('service_details')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('doctor_id')->references('id')->on('admins')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('secratry_id')->references('id')->on('admins')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('asistant_id')->references('id')->on('admins')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('adviser_id')->references('id')->on('advisers')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('reception_id')->references('id')->on('admins')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('complication_id')->references('id')->on('complication_items')
                ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_reserves');
    }
}
