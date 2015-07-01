<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Dajiayao\User;
use Dajiayao\Model\Supplier;
use Dajiayao\Model\Shop;
use Dajiayao\Model\Item;
use Dajiayao\Model\ItemType;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

//		 $this->call('UserTableSeeder');

        DB::table('users')->truncate();
        User::create([
            'id'=>1,
            'username' => 'admin',
            'email' => 'mall_admin@dajiayao.cc',
            'password' => \Hash::make('11431143'),
            'role' => User::ROLE_ADMIN, // 管理员
            'status' => User::STATUS_NORMAL,
        ]);

        DB::table('suppliers')->truncate();
        Supplier::create([
            'id'=>1,
            'username' => 'nanpufood',
            'email' => 'marketing@nanpufood.com',
            'title' => '南浦集团',
            'description' => '快速消费品综合代理和营销集团',
            'service_phone' => '021-61923399',
            'status' => 1
        ]);


        //店铺
        DB::table('shops')->truncate();
        Shop::create([
            'id'=>1,
            'short_id'=>"yayao",
            'province_id'=>310000,
            'city_id'=>310100,
            'county_id'=>310110,
            'type'=>2,
            'name'=>'丫摇官方直营店',
            'seller_id'=>1,
            'thumbnail'=>'',
            'title'=>'丫摇小店',
            'subtitle'=>'丫摇官方直营店',
            'is_direct_sale'=>Shop::IS_DIRECT_SALE_YES,
            'banner'=>'/images/banner_7.jpg',
            'thumbnail'=>'/images/logo_160.png'
        ]);
        //卖家
        DB::table('sellers')->truncate();
        \Dajiayao\Model\Seller::create([
            'id'=>1,
            'wx_user_id'=>1,
            'mobile'=>'15995420354',
            'parent_id'=>0,
            'realname'=>'丫摇官方店主'
        ]);
        \Dajiayao\Model\Seller::create([
            'id' => 2,
            'wx_user_id' => 2,
            'mobile' => '13800138000',
            'parent_id' => 0,
            'realname'=>'测试店主'
        ]);

        //微信user
        DB::table('wx_users')->truncate();
        \Dajiayao\Model\WxUser::create([
            'id'=>1,
            'subscribe'=>1,
            'openid'=>'oVtn9t9nmKQodGDQHLHfbHE_CDPI',
            'nickname'=>'小明',
            'sex'=>1,
            'city'=>'苏州',
            'country'=>'中国',
            'province'=>'江苏',
            'language'=>'zh_CN',
            'role'=>2,
            'headimgurl'=>'http://wx.qlogo.cn/mmopen/NWsF4EmJMsG8AeztibfdK5NWzAN6oX6PEwUOzeia9gzTvKW753aV0icuqTetiap4Tgf7q5sEdIaEstoa9hvvdlesuw/0'

        ]);
        \Dajiayao\Model\WxUser::create([
            'id'=>2,
            'subscribe'=>1,
            'openid'=>'oVtn9t9nmKQodGDQHLHfbHE_CDPI',
            'nickname'=>'小明2',
            'sex'=>1,
            'city'=>'苏州',
            'country'=>'中国',
            'province'=>'江苏',
            'language'=>'zh_CN',
            'role'=>2,
            'headimgurl'=>'http://wx.qlogo.cn/mmopen/NWsF4EmJMsG8AeztibfdK5NWzAN6oX6PEwUOzeia9gzTvKW753aV0icuqTetiap4Tgf7q5sEdIaEstoa9hvvdlesuw/0'

        ]);

        DB::table('items')->truncate();
        Item::create([
            'id'=>1,
            'name' => '星巴克 星冰乐咖啡',
            'title' => '星巴克 星冰乐咖啡',
            'code' => '1234',
            'barcode' => '678952135',
            'type_id' => 1,
            'supplier_id' => 1,
            'spec' => '摩卡味 281ml',
            'weight' => 281,
            'volume' => 281,
            'price' => 18,
            'market_price' => 28,
            'stock' => 10000,
            'shelf_status' => 1,
            'sale_status' => 1,
            'comment' => '真便宜啊',
            'postage_type' => 2
        ]);
        Item::create([
            'id'=>2,
            'name' => '星巴克 星冰乐咖啡',
            'title' => '星巴克 星冰乐 香草味 咖啡饮料 281ml*6美国进口',
            'code' => '1235',
            'barcode' => '678952185',
            'type_id' => 1,
            'supplier_id' => 1,
            'spec' => '摩卡味 281ml * 6',
            'weight' => 1686,
            'volume' => 1686,
            'price' => 108,
            'market_price' => 168,
            'stock' => 10000,
            'shelf_status' => 1,
            'sale_status' => 1,
            'comment' => '真的很便宜啊',
            'postage_type' => 2
        ]);
        Item::create([
            'id'=>3,
            'name' => '星巴克 星冰乐咖啡',
            'title' => '星巴克 星冰乐 摩卡 咖啡饮料 281ml*6美国进口',
            'code' => '1236',
            'barcode' => '678952165',
            'type_id' => 1,
            'supplier_id' => 1,
            'spec' => '摩卡味 281ml * 6',
            'weight' => 1686,
            'volume' => 1686,
            'price' => 108,
            'market_price' => 168,
            'stock' => 10000,
            'shelf_status' => 1,
            'sale_status' => 1,
            'comment' => '买买买啊',
            'postage_type' => 2
        ]);



        DB::table('shop_items')->truncate();
        \Dajiayao\Model\ShopItem::create([
            "shop_id"=>1,
            "item_id"=>1,
            "stock"=>1000,
            "sort"=>1,
            "is_single"=>1,
        ]);

        \Dajiayao\Model\ShopItem::create([
            "shop_id"=>1,
            "item_id"=>2,
            "stock"=>1000,
            "sort"=>1,
            "is_single"=>1,
        ]);

        \Dajiayao\Model\ShopItem::create([
            "shop_id"=>1,
            "item_id"=>3,
            "stock"=>1000,
            "sort"=>1,
            "is_single"=>1,
        ]);

        DB::table('images')->truncate();
        \Dajiayao\Model\Image::create([
           'url'=>'/images/product.jpg',
            'name'=>'星巴克',
            'type'=>1
        ]);

        DB::table('item_images')->truncate();
        \Dajiayao\Model\ItemImage::create([
            'item_id'=>1,
            'image_id'=>1
        ]);

        \Dajiayao\Model\ItemImage::create([
            'item_id'=>2,
            'image_id'=>1
        ]);

        \Dajiayao\Model\ItemImage::create([
            'item_id'=>3,
            'image_id'=>1
        ]);


        DB::table('item_type')->truncate();
        ItemType::create([
            'id'=>1,
            'name' => '饮料',
            'sort' => 1
        ]);
        ItemType::create([
            'id'=>2,
            'name' => '酒类',
            'sort' => 2
        ]);
        ItemType::create([
            'id'=>3,
            'name' => '坚果',
            'sort' => 3
        ]);

        DB::table('expresses')->truncate();
        \Dajiayao\Model\Express::create([
            'name' => '圆通',
            'code' => 'yuantong',
            'website' => 'http://www.yto.net.cn',
            'phone' => '95554'
        ]);
        \Dajiayao\Model\Express::create([
            'name' => '申通',
            'code' => 'shentong',
            'website' => 'http://www.sto.cn',
            'phone' => '95543'
        ]);
        \Dajiayao\Model\Express::create([
            'name' => '韵达',
            'code' => 'yunda',
            'website' => 'http://www.yundaex.com',
            'phone' => '400-821-6789'
        ]);
        \Dajiayao\Model\Express::create([
            'name' => '顺丰',
            'code' => 'shunfeng',
            'website' => 'http://www.sf-express.com',
            'phone' => '95338'
        ]);

        DB::table('settings')->truncate();
        \Dajiayao\Model\Setting::create([
            'key' => 'commissions:rate',
            'name' => '佣金比例',
            'value' => '0.1',
            'description' => '佣金 ＝ 实际成交价 x 佣金比例',
            'parent_id' => 0
        ]);
        \Dajiayao\Model\Setting::create([
            'key' => 'order:payment:duration',
            'name' => '订单最晚支付时间',
            'value' => '1',
            'parent_id' => 0
        ]);
        \Dajiayao\Model\Setting::create([
            'key' => 'order:auto:receive:duration',
            'name' => '发货后自动收货时间',
            'value' => '36',
            'parent_id' => 0
        ]);

        \Dajiayao\Model\Setting::create([
            'key' => 'order:postage',
            'name' => '统一邮费',
            'value' => '10',
            'parent_id' => 0
        ]);

        DB::table('buyers')->truncate();
        \Dajiayao\Model\Buyer::create([
            'id' => 2,
            'wx_user_id' => 2,
            'mobile' => '18600186000',
            'subscribe_status' => 1
        ]);

        DB::table('buyer_addresses')->truncate();
        \Dajiayao\Model\BuyerAddress::create([
            'buyer_id'=>2,
            'address_id'=>320507,
            'address'=>'相城区元和街道',
            'postcode'=>215000,
            'receiver'=>'zzq',
            'mobile'=>159987548,
            'default'=>1
        ]);

	}

}
