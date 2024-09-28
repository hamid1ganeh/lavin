<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('mobile');
            $table->text('information')->nullable();
            $table->unsignedBigInteger('management_id')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->DateTime('operator_date_time')->nullable();
            $table->text('operator_description')->nullable();
            $table->DateTime('accept_date_time')->nullable();
            $table->string('status')->default(0);
            $table->string('type',2)->default(0);
            $table->unsignedBigInteger('festival_id')->nullable();
            $table->string('festival')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('management_id')
            ->on('admins')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('operator_id')
                ->on('admins')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('festival_id')
                ->on('festivals')
                ->references('id')
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
        Schema::dropIfExists('numbers');
    }
}
