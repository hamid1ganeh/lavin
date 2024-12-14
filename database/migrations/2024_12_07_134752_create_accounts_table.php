<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('bank_name');
            $table->string('account_type');
            $table->dateTime('opened_at');
            $table->string('account_number');
            $table->string('cart_number');
            $table->string('shaba_number');
            $table->unsignedSmallInteger('pos')->default(false);
            $table->unsignedSmallInteger('status')->default(Status::Active);
            $table->softDeletes();
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
        Schema::dropIfExists('accounts');
    }
}
