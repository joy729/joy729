<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_master', function (Blueprint $table) {
            $table->increments('order_master_no');
            $table->integer('user_no');
            $table->text('order_id');
            $table->integer('city_no');
            $table->integer('area_no');
            $table->text('order_address');
            $table->integer('payment_type');
            $table->integer('transaction_id');
            $table->double('orderAmount');
            $table->double('delivery_charge');
            $table->double('total_amount');
            $table->double('paid_amount');
            $table->double('due_amount');
            $table->integer('is_paid')->default(0);
            $table->integer('order_status_no')->default(1);;
            $table->integer('approved_by');
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('order_master');
    }
}
