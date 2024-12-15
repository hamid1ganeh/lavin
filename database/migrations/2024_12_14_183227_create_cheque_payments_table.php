<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentType;
class CreateChequePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->unsignedBigInteger('passed_by_account_id')->nullable();
            $table->string('serial_number');
            $table->string('sender_full_name');
            $table->string('sender_nation_code');
            $table->string('sender_account_number');
            $table->unsignedBigInteger('price');
            $table->dateTime('date_of_issue');
            $table->dateTime('due_date');
            $table->unsignedBigInteger('cashier_id');
            $table->boolean('passed')->default(false);
            $table->dateTime('passed_date')->nullable();
            $table->string('description')->nullable();
            $table->string('type',1)->default(PaymentType::income);
            $table->timestamps();

            $table->foreign('passed_by_account_id')
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
        Schema::dropIfExists('cheque_payments');
    }
}
