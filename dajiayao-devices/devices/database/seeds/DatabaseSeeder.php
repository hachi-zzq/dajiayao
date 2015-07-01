<?php

use Dajiayao\Model\WeixinMp;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Dajiayao\User;
use Dajiayao\Model\Manufacturer;
use Dajiayao\Model\DeviceModel;
use Dajiayao\Model\App;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('UserTableSeeder');
        DB::table('users')->truncate();
        User::create([
            'username' => 'admin',
            'email' => 'device_admin@dajiayao.cc',
            'password' => \Hash::make('11431143'),
            'role' => User::ROLE_ADMIN, // 管理员
            'status' => User::STATUS_NORMAL,
        ]);
        User::create([
            'username' => 'yayao001',
            'email' => 'luyu@dajiayao.cc',
            'password' => \Hash::make('yayao001'),
            'role' => User::ROLE_COMMON_USER, // 管理员
            'status' => User::STATUS_NORMAL,
        ]);

        DB::table('manufacturers')->truncate();
        Manufacturer::create([
            'name' => '佰睿科技',
            'website' => 'http://www.bytereal.com',
            'address' => '深圳市南山区高新南六道航盛科技大厦',
            'email' => 'jeffweng@bytereal.com',
            'phone' => '400-8899-181'
        ]);
        Manufacturer::create([
            'name' => '微肯科技',
            'website' => 'http://www.wizarcan.com',
            'address' => '上海市黄浦区西藏南路760号安基大厦706室',
            'email' => 'contact@wizarcan.com',
            'phone' => '021-63309703'
        ]);

        DB::table('device_models')->truncate();
        DeviceModel::create([
            'id'=>1,
            'manufacturer_id' => 1,
            'battery_lifetime' => 18,
            'name' => 'BR',
            'comment' => '佰睿'
        ]);
        DeviceModel::create([
            'id'=>2,
            'manufacturer_id' => 2,
            'battery_lifetime' => 18,
            'name' => 'wizarcan',
            'comment' => '微肯'
        ]);

        DB::table('apps')->truncate();
        App::create([
            'app_id' => 'yyfa7b475d22b0ef1d',
            'app_secret' => 'ec6a2a765bc197a273841cd41ed171a2 ',
            'name' => '丫摇小店',
            'type' => App::TYPE_WEIXIN,
            'access_token' => '',
            'expire_at' => '2030-05-01 00:00:00',
            'comment' => '丫摇小店(消费者服务号)',
            'user_id'=>2,
            'status' => App::STATUS_NORMAL
        ]);

        App::create([
            'app_id' => 'yy3a6b475fc2b19f2e',
            'app_secret' => '3a76597a2784bc11edeccd416a2171a2',
            'name' => '测试应用',
            'type' => App::TYPE_WEIXIN,
            'access_token' => '',
            'expire_at' => '2030-05-01 00:00:00',
            'comment' => '测试应用',
            'user_id'=>2,
            'status' => App::STATUS_NORMAL
        ]);

        DB::table('wx_mp')->truncate();
        WeixinMp::create([
            'appid' => 'wxfb42b0ea75d27f1d',
            'appsecret' => 'a62a7197273e81cd4a1d14715bca2ec6',
            'name' => '丫摇小店',
            'mp_id' => 'gh_dd4f2e417685',
            'comment' => '丫摇小店(消费者服务号)',
            'app_id'=>1
        ]);
        WeixinMp::create([
            'appid' => 'wxbc879a670cb32971',
            'appsecret' => '20b517a9882cecc620e20175f8943ef0',
            'name' => '测试号',
            'mp_id' => 'gh_dxxxxxxxx',
            'comment' => '测试',
            'app_id'=>2
        ]);

        DB::table('devices')->truncate();

//        \Dajiayao\Model\Device::create([
//            "id"=>1,
//            "model_id"=>1,
//            "manufacturer_sn"=>11111,
//            "sn"=>2015121212,
//            "wx_device_id"=>1,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44330,
//            "password"=>123456,
//            "comment"=>'comment',
//            "status"=>1
//        ]);
//
//        \Dajiayao\Model\Device::create([
//            "id"=>2,
//            "model_id"=>1,
//            "manufacturer_sn"=>22222,
//            "sn"=>2015131313,
//            "wx_device_id"=>1,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44330,
//            "password"=>123456,
//            "comment"=>'comment',
//            "status"=>1
//        ]);
//
//        \Dajiayao\Model\Device::create([
//            "id"=>3,
//            "model_id"=>1,
//            "manufacturer_sn"=>33333,
//            "sn"=>2015141414,
//            "wx_device_id"=>2,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44331,
//            "password"=>123456,
//            "comment"=>'comment',
//            "status"=>1
//        ]);
//
//        \Dajiayao\Model\Device::create([
//            "id"=>4,
//            "model_id"=>1,
//            "manufacturer_sn"=>44444,
//            "sn"=>201515151515,
//            "wx_device_id"=>3,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44332,
//            "password"=>123456,
//            "comment"=>'comment',
//            "status"=>1
//        ]);

        DB::table('wx_devices')->truncate();

//        \Dajiayao\Model\WeixinDevice::create([
//            'id'=>1,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44330,
//            "device_id"=>657415,
//            "wx_mp_id"=>1
//        ]);
//
//
//        \Dajiayao\Model\WeixinDevice::create([
//            'id'=>2,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44331,
//            "device_id"=>657416,
//            "wx_mp_id"=>1
//        ]);
//
//        \Dajiayao\Model\WeixinDevice::create([
//            'id'=>3,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10001,
//            "minor"=>44332,
//            "device_id"=>657417,
//            "wx_mp_id"=>1
//        ]);
//
//        \Dajiayao\Model\WeixinDevice::create([
//            'id'=>4,
//            "uuid"=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825',
//            "major"=>10007,
//            "minor"=>41549,
//            "device_id"=>681062,
//            "wx_mp_id"=>1,
//            "apply_id"=>26858
//        ]);


        DB::table('wx_pages')->truncate();
//        \Dajiayao\Model\WeixinPage::create([
//            "wx_mp_id"=>1,
//            "page_id"=>81039,
//            'title'=>'周边主标题',
//            'description'=>'副标题',
//            'icon_url'=>'http://shp.qpic.cn/wx_shake_bus/0/1431503979e9dd2797018cad79186e03e8c5aec8dc/120',
//            'url'=>'http://www.baidu.com',
//            'comment'=>'comment'
//        ]);
//
//        DB::table('wx_mp')->truncate();
//        \Dajiayao\Model\WeixinMp::create([
//            "id"=>1,
//            "appid"=>'wxbc879a670cb32971',
//            'appsecret'=>'20b517a9882cecc620e20175f8943ef0',
//            'name'=>'大家摇',
//            'mp_id'=>'原始id',
//            'app_id'=>1
//        ]);
    }

}
