<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentType;
class CreatePosPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->unsignedBigInteger('receiver_account_id')->nullable();
            $table->unsignedBigInteger('sender_account_id')->nullable();
            $table->string('transaction_number');
            $table->unsignedBigInteger('price');
            $table->dateTime('paid_at');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('cashier_id');
            $table->string('type',1)->default(PaymentType::income);
            $table->timestamps();

            $table->foreign('receiver_account_id')
                   ->references('id')
                   ->on('accounts')
                   ->onDelete('cascade')
                   ->onUpdate('cascade');

            $table->foreign('sender_account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('cashier_id')
                ->references('id')
                ->on('admins')
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
        Schema::dropIfExists('pos_payments');
    }
}
