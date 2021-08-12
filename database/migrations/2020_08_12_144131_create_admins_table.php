<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('admin_no');
            $table->string('admin_name');
            $table->string('admin_email')->unique();
            $table->string('admin_phone')->unique()->nullable();
            $table->text('admin_address')->nullable();
            $table->string('password');
            $table->string('admin_photo')->nullable();
            $table->integer('user_role')->default(0);
            $table->integer('is_active')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
}
