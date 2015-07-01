<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsCodeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 短信验证码表
        Schema::create('sms_code', function(Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->comment("手机号");
            $table->string('code')->comment("验证码");
            $table->string('ip');
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
