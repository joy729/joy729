<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_no');
            $table->integer('user_no');
            $table->integer('category_no');
            $table->integer('subcategory_no');
            $table->string('product_title');
            $table->string('product_slug');
            $table->double('unit_price');
            $table->integer('unit_no');
            $table->double('unit_quantity');
            $table->text('product_details');
            $table->text('product_additional_info')->nullable();
            $table->string('product_image');
            $table->string('product_image2')->nullable();
            $table->integer('is_approved')->default(0);
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
        Schema::dropIfExists('products');
    }
}
