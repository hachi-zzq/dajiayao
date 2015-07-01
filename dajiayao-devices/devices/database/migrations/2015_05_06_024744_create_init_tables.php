<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitTables extends Migration {

    /**
     * Run the migrations.
     * @author Hanxiang
     * @return void
     */
    public function up()
    {
        // devices 设备表
        Schema::create('devices', function(Blueprint $table){
            $table->increments('id');
            $table->integer('model_id')->unsigned()->comment('型号ID');
            $table->string('manufacturer_sn')->comment('厂家SN编号');
            $table->string('sn')->unique()->comment('平台SN编号');
            $table->integer('wx_device_id')->unsigned()->comment('微信设备表id');
            $table->string('uuid', 36);
            $table->integer('major')->unsigned();
            $table->integer('minor')->unsigned();
            $table->string('password', 32)->nullable()->comment('设备连接密码');
            $table->timestamp('power_outage_date')->comment('电量预计耗尽时间');
            $table->decimal('longitude', 7, 4)->nullable()->comment('经度');
            $table->decimal('latitude', 7, 4)->nullable()->comment('纬度');
            $table->string('address')->nullable()->comment('地址');
            $table->string('position')->nullable()->comment('位置');
            $table->smallInteger('status');
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // device_app 设备与应用关联表
        Schema::create('device_app', function(Blueprint $table){
            $table->increments('id');
            $table->integer('device_id')->unsigned()->comment('设备表主键');
            $table->integer('app_id')->unsigned()->comment('应用ID');
            $table->timestamps();
        });

        // apps 应用表
        Schema::create('apps', function(Blueprint $table){
            $table->increments('id');
            $table->string('app_id');
            $table->string('app_secret');
            $table->string('name')->comment('应用名称');
            $table->smallInteger('type')->default(1)->comment('应用类型');
            $table->string('access_token')->nullable();
            $table->timestamp('expire_at')->comment('access_token 过期过期时间');
            $table->string('device_url')->nullable()->comment('设备激活入口url');
            $table->string('comment')->nullable();
            $table->smallInteger('status');
            $table->integer('user_id')->unsigned()->comment('应用所属用户');
            $table->timestamps();
            $table->softDeletes();
        });

        // wx_device  微信设备表
        Schema::create('wx_devices', function(Blueprint $table){
            $table->increments('id');
            $table->string('uuid', 36);
            $table->integer('major')->unsigned();
            $table->integer('minor')->unsigned();
            $table->integer('device_id')->unsigned()->comment('微信平台的设备id');
            $table->integer('apply_id')->unsigned()->comment('申请批次');
            $table->integer('wx_mp_id')->unsigned()->comment('公众号id');
            $table->string('comment')->nullable();
            $table->string('redirect_url')->nullable()->comment('重定向地址');
            $table->string('redirect_name')->nullable()->comment('重定向名称');
            $table->smallInteger('status')->default(0)->comment('激活状态，0：未激活，1：已激活（但不活跃），2：活跃');
            $table->smallInteger('poi_id')->default(0)->comment('设备关联的门店ID');
            $table->timestamps();
            $table->softDeletes();
        });

        // wx_pages 微信页面表
        Schema::create('wx_pages', function(Blueprint $table){
            $table->increments('id');
            $table->string('guid')->comment('guid');
            $table->integer('wx_mp_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->string('title')->comment('标题');
            $table->string('description')->nullable()->comment('描述');
            $table->string('icon_url')->nullable();
            $table->string('url')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // device_page 微信设备页面关联表
        Schema::create('device_page', function(Blueprint $table){
            $table->integer('wx_device_id')->unsigned();
            $table->integer('wx_page_id')->unsigned();
            $table->primary(array('wx_device_id', 'wx_page_id')); //复合主键
        });

        // device_models 型号表
        Schema::create('device_models', function(Blueprint $table){
            $table->increments('id');
            $table->integer('manufacturer_id')->unsigned()->comment('厂家id');
            $table->string('name')->comment('设备型号名称');
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // manufacturers 厂商表
        Schema::create('manufacturers', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // wx_mp 微信公众号表
        Schema::create('wx_mp', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->comment('公众号名称');
            $table->string('mp_id')->comment('公众号id');
            $table->string('appid')->comment('公众号appid');
            $table->string('appsecret')->nullable()->comment('密钥');
            $table->smallInteger('status');
            $table->string('comment')->nullable();
            $table->integer('app_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        // wx_page_statistics 微信页面摇一摇统计
        Schema::create('wx_page_statistics', function(Blueprint $table){
            $table->increments('id');
            $table->string('mp_id')->comment('公众号id');
            $table->integer('page_id')->comment('页面id');
            $table->integer('wx_page_id')->unsigned()->comment('微信页面表id');
            $table->integer('wx_mp_id')->unsigned()->comment('微信公众号表id');
            $table->integer('click_pv')->default(0)->comment('点击摇周边消息的次数');
            $table->integer('click_uv')->default(0)->comment('点击摇周边消息的人数');
            $table->integer('shake_pv')->default(0)->comment('摇周边的次数');
            $table->integer('shake_uv')->default(0)->comment('摇周边的人数');
            $table->timestamps();
            $table->softDeletes();
        });

        // wx_device_statistics 微信设备摇一摇统计
        Schema::create('wx_device_statistics', function(Blueprint $table){
            $table->increments('id');
            $table->string('mp_id')->comment('公众号id');
            $table->integer('wx_mp_id')->unsigned()->comment('微信公众号表id');
            $table->integer('wx_device_id')->unsigned()->comment('微信设备表id');
            $table->integer('device_id')->unsigned()->comment('微信device_id');
            $table->integer('click_pv')->default(0)->comment('点击摇周边消息的次数');
            $table->integer('click_uv')->default(0)->comment('点击摇周边消息的人数');
            $table->integer('shake_pv')->default(0)->comment('摇周边的次数');
            $table->integer('shake_uv')->default(0)->comment('摇周边的人数');
            $table->timestamps();
            $table->softDeletes();
        });

        // weixin_user 微信用户表
        Schema::create('wx_users', function(Blueprint $table){
            $table->increments('id');
            $table->smallInteger('subscribe')->default(0);
            $table->string('open_id');
            $table->string('nickname')->nullable();
            $table->smallInteger('sex')->default(0);
            $table->string('language')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('headimgurl')->nullable();
            $table->timestamp('subscribe_time');
            $table->string('unionid')->nullable();
            $table->string('mp_id')->comment('公众号id');
            $table->integer('wx_mp_id')->unsigned()->comment('微信公众号表id');
            $table->timestamps();
            $table->softDeletes();
        });


        // user_kv  用户表配置表
        Schema::create('user_kv', function(Blueprint $table){
            $table->increments('id');
            $table->string('key');
            $table->string('value')->nullable();
            $table->integer('parent_id')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rest_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('request')->nullable();
            $table->string('request_route')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('client_useragent')->nullable();
            $table->string('client_ip', 15);
            $table->string('msgcode', 6)->nullable();
            $table->text('message')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });

        // shake_info 摇一摇数据信息
        Schema::create('shake_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->integer('wx_page_id')->unsigned()->comment('微信页面表id');
            $table->integer('wx_device_id')->unsigned()->comment('微信设备表id');
            $table->float('distance')->comment('距离');
            $table->string('uuid', 36);
            $table->integer('major')->unsigned();
            $table->integer('minor')->unsigned();
            $table->string('openid');
            $table->integer('poi_id')->unsigned()->nullable()->comment('微信门店id');
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
		//
	}

}
