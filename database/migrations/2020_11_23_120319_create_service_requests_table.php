<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->increments('service_request_no');
            $table->integer('service_no');
            $table->integer('user_no');
            $table->integer('assigned_sp_no')->nullable();
            $table->integer('category_no');
            $table->integer('subcategory_no');
            $table->string('order_number');
            $table->date('order_date');
            $table->time('order_time');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->double('service_hours')->nullable();
            $table->double('total_service_cost')->nullable();
            $table->string('order_address');
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('service_provider_note')->nullable();
            $table->integer('order_status_no')->default(1);
            $table->integer('canceled_by_customer')->default(0);
            $table->integer('is_assigned')->default(0);
            $table->integer('canceled_by')->default(0);
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
        Schema::dropIfExists('service_requests');
    }
}
