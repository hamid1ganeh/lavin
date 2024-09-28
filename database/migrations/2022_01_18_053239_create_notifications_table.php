<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('admin_id');
            $table->string('type',10)->default('user');
            $table->tinyInteger('status')->default(0);
            $table->boolean('sms')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('notification_user', function (Blueprint $table) {

            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('seen')->default(0);

            $table->foreign('notification_id')->references('id')->on('notifications')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['notification_id','user_id']);
        });

        Schema::create('notification_admin', function (Blueprint $table) {

            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('admin_id');
            $table->tinyInteger('seen')->default(0);

            $table->foreign('notification_id')->references('id')->on('notifications')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('admin_id')->references('id')->on('admins')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['notification_id','admin_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
