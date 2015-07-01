<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // items 商品表
        Schema::create('items', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->string('title')->nullable()->comment('标题');
            $table->string('code')->unique()->comment('编码');
            $table->string('barcode')->unique()->comment('条形码');
            $table->integer('type_id')->unsigned()->comment('商品类型ID');
            $table->integer('supplier_id')->unsigned()->comment('供应商ID');
            $table->string('spec')->nullable()->comment('规格');
            $table->float('weight')->nullable()->comment('重量');
            $table->float('volume')->nullable()->comment('体积');
            $table->decimal('price')->nullable()->comment('单价');
            $table->decimal('market_price')->nullable()->comment('市场价');
            $table->integer('stock')->comment("库存");
            $table->decimal('commission_ratio')->nullable()->comment('拥金比例');
            $table->decimal('commission')->nullable()->comment('拥金');
            $table->smallInteger('postage_type')->comment("邮费类型，1:买家承担,2:卖家承担");
            $table->smallInteger('shelf_status')->comment("上架状态");
            $table->smallInteger('sale_status')->comment("销售状态");
            $table->string('is_direct_sale',1)->default('N')->comment("是否是直营商品");
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // items_type 商品类型
        Schema::create('item_type', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->comment('类型名');
            $table->integer('sort')->unsigned()->comment('排序');
            $table->timestamps();
        });

        // order 订单表
        Schema::create('order', function(Blueprint $table){
            $table->increments('id');
            $table->string('order_number')->comment("订单编号");
            $table->integer('shop_id')->unsigned()->comment('店铺ID');
            $table->integer('buyer_id')->unsigned()->comment('买家ID');
            $table->decimal('item_total')->nullable()->comment('总价');
            $table->decimal('grand_total')->nullable()->comment('应付总额');
            $table->decimal('discount_total')->nullable()->comment('优惠总额');
            $table->decimal('amount_tendered')->nullable()->comment('实付总额');

            $table->decimal('postage')->nullable()->comment('邮费');
            $table->smallInteger('order_type')->comment("订单类型");
            $table->smallInteger('delivery_method')->comment("运送方式");
            $table->integer('express_id')->unsigned()->comment('快递公司ID');
            $table->string('express_number')->nullable()->comment('快递单号');

            $table->integer('receiver_address_id')->unsigned()->comment('地址ID');
            $table->string('receiver_address')->comment('收件具体地址');
            $table->string('receiver_full_address')->comment('收件地址完整形式');
            $table->string('receiver')->comment('收件人');
            $table->string('receiver_phone')->comment('收件人电话');
            $table->string('receiver_postcode')->comment('收件人邮编');

            $table->string('sender')->comment('发件人');
            $table->string('sender_address')->comment('发件地址');
            $table->string('sender_phone')->comment('发件电话');
            $table->string('sender_postcode')->comment('发件邮编');

            $table->boolean('is_anonymous')->comment('是否匿名购买');
            $table->smallInteger('deliver_status')->comment("发货状态");

            $table->string('payment_serial_number')->nullable()->comment('支付单号');
            $table->integer('payment_id')->nullable()->comment('支付单ID');
            $table->smallInteger('payment_status')->comment("支付状态");
            $table->string('payment_type')->comment("支付方式");

            $table->smallInteger('status')->comment("状态");
            $table->string('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // order_items 订单商品表
        Schema::create('order_items', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned()->comment('订单ID');
            $table->integer('item_id')->comment("商品ID");
            $table->string('name')->comment('名称');
            $table->string('title')->nullable()->comment('标题');
            $table->string('code')->comment('编码');
            $table->string('barcode')->comment('条形码');
            $table->string('type')->comment('商品类型');
            $table->integer('quantity')->unsigned()->comment('数量');
            $table->decimal('price')->nullable()->comment('单价');
            $table->decimal('item_total')->nullable()->comment('总价');
            $table->decimal('commission')->nullable()->comment('拥金');
            $table->string('comment')->nullable();
            $table->string('url')->nullable();
            $table->unique(array('order_id','item_id'));
            $table->timestamps();
            $table->softDeletes();
        });

        // order_commissions 订单佣金
        Schema::create('order_commissions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned()->comment('订单ID');
            $table->decimal('amount')->comment('拥金总额');
            $table->smallInteger('status')->comment("状态");
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        // seller_commissions 店主佣金详细
        Schema::create('seller_commissions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned()->comment('订单ID');
            $table->integer('seller_id')->unsigned()->comment('卖家ID');
            $table->decimal('amount')->comment('拥金总额');
            $table->smallInteger('status')->comment("状态");
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // withdraw_commissions  佣金提现
        Schema::create('withdraw_commissions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('withdraw_number')->nullable()->comment('提现申请编号');
            $table->integer('seller_id');
            $table->decimal('amount')->comment('拥金提现总额');
            $table->string('account_name')->nullable()->comment("账户姓名");
            $table->string('account_number')->nullable()->comment("银行卡号");
            $table->string('opening_bank')->nullable()->comment("开户行");
            $table->smallInteger('status')->comment("状态");
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // payments 支付
        Schema::create('payments', function(Blueprint $table){
            $table->increments('id');
            $table->string('serial_number')->unique()->comment("支付流水号");
            $table->string('payment_number')->comment("支付渠道的流水号");
            $table->integer('order_id')->unsigned()->comment('订单ID');
            $table->string('order_number')->comment("订单编号");
            $table->integer('buyer_id')->comment('买家ID');
            $table->decimal('amount')->comment('金额');
            $table->string('channel')->comment("支付渠道");
            $table->string('type')->comment("支付类型");
            $table->smallInteger('status')->comment("状态");
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // payment_log 支付日志
        Schema::create('payment_log', function(Blueprint $table){
            $table->increments('id');
            $table->integer('payment_id')->unsigned()->comment("支付ID");
            $table->string('channel')->comment("支付渠道");
            $table->string('request_data')->nullable()->comment("支付请求数据");
            $table->string('respond_data')->nullable()->comment("支付返回数据");
            $table->timestamps();
            $table->softDeletes();
        });

        // 卖家表
        Schema::create('sellers', function(Blueprint $table){
            $table->increments('id');
            $table->integer('wx_user_id')->comment("微信用户表ID");
            $table->string('mobile')->nullable();
            $table->smallInteger('status')->default(1);
            $table->string('realname')->nullable()->comment("真实姓名");
            $table->string('account_number')->nullable()->comment("银行卡号");
            $table->string('opening_bank')->nullable()->comment("开户行");
            $table->string('openbank_address')->nullable()->comment("支行地址");
            $table->smallInteger('auth_status')->default(0)->comment("认证状态，0否，1是");
            $table->decimal('commission')->default(0)->comment("可提佣金");
            $table->integer('parent_id')->nullable()->comment("上级卖家");
            $table->timestamps();
            $table->softDeletes();
        });

        // 买家表
        Schema::create('buyers', function(Blueprint $table){
            $table->increments('id');
            $table->integer('wx_user_id')->comment("微信用户表ID");
            $table->string('mobile')->nullable();
            $table->smallInteger('subscribe_status')->default(1)->comment("是否关注");
            $table->timestamps();
            $table->softDeletes();
        });

        // 店铺表
        Schema::create('shops', function(Blueprint $table){
            $table->increments('id');
            $table->string('short_id')->comment("");
            $table->string('name')->comment("名称");
            $table->integer('province_id')->unsigned()->comment('所属省ID');
            $table->integer('city_id')->unsigned()->comment('所属市ID');
            $table->integer('county_id')->unsigned()->comment('所属区ID');
            $table->string('banner')->comment('banner');
            $table->smallInteger('type')->default(1)->comment("类型：固定点，直销");
            $table->smallInteger('mode')->default(1)->comment("模式：普通，单品");
            $table->string('is_direct_sale',1)->default('N')->comment("是否是直营店");
            $table->integer('seller_id')->comment("卖家ID");
            $table->string('thumbnail')->comment("缩略图");
            $table->string('title')->comment("标题");
            $table->string('subtitle')->comment("副标题");
            $table->smallInteger('open_status')->default(1)->comment("开店营业状态");
            $table->string('url')->nullable()->comment("主页URL");
            $table->integer('page_id')->nullable();
            $table->string('comment')->nullable();
            $table->smallInteger('status')->default(1)->comment("状态");
            $table->timestamps();
            $table->softDeletes();
        });

        // 货架商品表
        Schema::create('shop_items', function(Blueprint $table){
            $table->increments('id');
            $table->integer('shop_id')->comment("店铺ID");
            $table->integer('item_id')->comment("商品ID");
            $table->integer('stock')->comment("库存");
            $table->integer('sort')->comment("排序");
            $table->smallInteger('status')->default(1)->comment("状态");
            $table->smallInteger('is_single')->default(0)->comment("是否是单品");
            $table->timestamps();
            $table->softDeletes();
        });

        // 收藏店铺表
        Schema::create('favorite_shops', function(Blueprint $table){
            $table->increments('id');
            $table->integer('buyer_id')->comment("用户ID");
            $table->integer('shop_id')->comment("店铺ID");
        });

        // 地址表
        Schema::create('addresses', function(Blueprint $table){
            $table->increments('id');
            $table->string('guid')->comment('guid');
            $table->string('address')->comment("地址名");
            $table->integer('parent_id')->nullable()->comment("上级ID");
            $table->integer('level')->nullable();
        });

        // 买家地址表
        Schema::create('buyer_addresses', function(Blueprint $table){
            $table->increments('id');
            $table->integer('buyer_id')->comment("买家ID");
            $table->integer('address_id')->comment("区县ID");
            $table->string('address')->comment("详细信息");
            $table->string('postcode')->comment("邮编");
            $table->string('receiver')->comment("收件人");
            $table->string('mobile')->comment("电话");
            $table->smallInteger('default')->default(0)->comment("是否默认");
            $table->timestamps();
            $table->softDeletes();
        });

        // 图片表
        Schema::create('images', function(Blueprint $table){
            $table->increments('id');
            $table->string('url')->comment("路径");
            $table->string('name');
            $table->smallInteger('type')->comment("类型");
            $table->timestamps();
            $table->softDeletes();
        });

        // 商品-图片表
        Schema::create('item_images', function(Blueprint $table){
            $table->increments('id');
            $table->integer('item_id')->comment("商品ID");
            $table->integer('image_id')->comment("图片ID");
        });

        // 快递公司表
        Schema::create('expresses', function(Blueprint $table){
            $table->increments('id');
            $table->string('code')->comment("公司代码");
            $table->string('name')->comment("名称");
            $table->string('website')->comment("网站地址");
            $table->string('phone');
            $table->integer('sort')->default(1);
            $table->smallInteger('status')->default(1)->comment("状态");
            $table->timestamps();
            $table->softDeletes();
        });

        // 店铺地址表
        Schema::create('shop_addresses', function(Blueprint $table){
            $table->increments('id');
            $table->integer('address_id')->comment("区县ID");
            $table->string('detail')->comment("详细地址");
            $table->integer('shop_id')->comment("店铺ID");
            $table->timestamps();
            $table->softDeletes();
        });

        // 店铺-设备表
        Schema::create('shop_devices', function(Blueprint $table){
            $table->increments('id');
            $table->integer('shop_id');
            $table->string('device_sn');
            $table->integer('dealer')->unsigned()->comment("代理商");
            $table->integer('wx_device_id')->comment("微信设备ID");
            $table->timestamps();
            $table->softDeletes();
        });

        // 配置表
        Schema::create('settings', function(Blueprint $table){
            $table->increments('id');
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('parent_id');
            $table->timestamps();
            $table->softDeletes();
        });

        // 供应商表
        Schema::create('suppliers', function(Blueprint $table){
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('service_phone');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // payment_type 支付类型表
        Schema::create('payment_types', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('type')->unique();
            $table->integer('sort')->default(1);
            $table->smallInteger('status')->comment("状态");
            $table->timestamps();
            $table->softDeletes();
        });

        // 微信用户表
        Schema::create('wx_users', function(Blueprint $table){
            $table->increments('id');
            $table->smallInteger('subscribe')->default(0)->comment("是否关注：0否，1是");
            $table->string('openid')->comment("微信用户标识");
            $table->string('nickname')->comment("微信用户昵称");
            $table->smallInteger('sex')->default(0)->comment("性别，1男性，2女性，0未知");
            $table->string('city')->nullable()->comment("用户所在城市");
            $table->string('country')->nullable()->comment("用户所在国家");
            $table->string('province')->nullable()->comment("用户所在省份");
            $table->string('language')->nullable()->comment("用户的语言，简体中文为zh_CN");
            $table->string('headimgurl')->nullable()->comment("用户头像");
            $table->integer('subscribe_time')->nullable()->comment("用户关注时间，为时间戳");
            $table->string('unionid')->nullable()->comment("只有在用户将公众号绑定到开放平台账号后才会有该字段");
            $table->string('remark')->nullable()->comment("公众号运营者对粉丝的备注");
            $table->integer('groupid')->nullable()->comment("用户所在分组的ID");
            $table->smallInteger('role')->default(1)->comment("本平台的角色：1买家，2卖家");
            $table->smallInteger('status')->default(1)->comment("状态");
            $table->timestamps();
            $table->softDeletes();
        });

        // 用户表
        Schema::create('users', function(Blueprint $table){
            $table->increments('id');
            $table->string('username', 128)->unique()->comment('用户名');
            $table->string('email', 64)->comment();
            $table->string('password', 128);
            $table->smallInteger('role')->comment('用户角色，1：管理员,2：代理商');
            $table->smallInteger('status')->comment('状态，0：有效，－1：无效');
            $table->timestamp('last_login_time')->nullable();
            $table->string('last_login_ip', 15)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rest_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('request')->nullable();
            $table->string('request_route')->nullable();
            $table->text('client_useragent')->nullable();
            $table->string('client_ip', 15);
            $table->string('msgcode', 6)->nullable();
            $table->text('message')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });

        Schema::create('wx_user_kv', function (Blueprint $table) {
            $table->increments('id');
            $table->text('wx_user_id')->nullable();
            $table->string('key')->nullable();
            $table->text('value')->nullable();
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
