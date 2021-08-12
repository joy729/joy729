<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatWiseUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_wise_users', function (Blueprint $table) {
            $table->increments('cat_wise_sp_no');
            $table->integer('category_no');
            $table->integer('subcategory_no');
            $table->integer('user_no');
            $table->integer('is_available')->default(1);
            $table->integer('canceled_service_req_no')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('cat_wise_users');
    }
}
