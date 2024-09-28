<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AnaliseStatus;

class CreateAnalyseReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyse_reserves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('analyse_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('price');
            $table->text('response')->nullable();
            $table->string('voice')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('status')->default( AnaliseStatus::pending);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('analyse_id')->references('id')->on('analyses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('doctor_id')->references('id')->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('image_id')->references('id')->on('images')
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
        Schema::dropIfExists('analyse_reserves');
    }
}
