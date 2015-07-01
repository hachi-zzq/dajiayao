<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 用户表
        Schema::create('users', function(Blueprint $table){
            $table->increments('id');
            $table->string('username', 128)->unique()->comment('用户名');
            $table->string('email', 64)->comment();
            $table->string('password', 128);
            $table->smallInteger('role')->comment('用户角色，1：管理员,2：普通用户');
            $table->smallInteger('status')->comment('状态，0：有效，－1：无效');
            $table->timestamp('last_login_time')->nullable();
            $table->string('last_login_ip', 15)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
