<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdviserHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adviser_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adviser_id');
            $table->unsignedBigInteger('admin_id');
            $table->dateTime('until')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('festival_id')->nullable();
            $table->dateTime('answered_at')->nullable();
            $table->timestamps();

            $table->foreign('adviser_id')
            ->on('advisers')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('admin_id')
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
        Schema::dropIfExists('adviser_histories');
    }
}
