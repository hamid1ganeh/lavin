<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\NumberStatus;

class CreateAdvisersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('number_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('adviser_id')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->unsignedBigInteger('arrangement_id')->nullable();
            $table->unsignedBigInteger('management_id')->nullable();
            $table->unsignedBigInteger('festival_id')->nullable();
            $table->DateTime('adviser_date_time')->nullable();
            $table->text('adviser_description')->nullable();
            $table->string('status')->default(NumberStatus::WaitingForAdviser);
            $table->boolean('in_person')->default(0);

            $table->foreign('number_id')->references('id')->on('numbers')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('service_id')->references('id')->on('service_details')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('operator_id')->references('id')->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('arrangement_id')->references('id')->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('management_id')->references('id')->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('adviser_id')   ->references('id')->on('admins')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('festival_id')
            ->on('festivals')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('number_services');
    }
}
