<?php

/**
 * 微信授权，用于获取微信的用户身份
 * @author zhengqian.zhu
 */
Route::group(['domain' => Config::get('app.domain.shop')], function() {

    Route::get('wx-auth', ['as' => 'wxShopAuth', 'uses' => 'WeixinAuth\WeixinShopAuthController@auth']);


    Route::get('/wx/auth-test',['middleware'=>'front.wx.shop.auth','uses'=>'WeixinShopController@test']);

    /**
     * 店铺首页
     */
    Route::get('{shop_short_id}',['middleware'=>'front.wx.shop.auth','as'=>'shopIndex','uses'=>'WeixinShopController@index'])->where('shop_short_id','[^\.\/]+');


    /**
     * p++ 支付结果的回调地址
     */
    Route::post('/payment/callback',['as'=>'wxPayCallBack','uses'=>'WeixinShopController@payCallBack']);


    /**
     * 买家页面
     */
    Route::group(['prefix'=>'buyer/orders','namespace'=>'Buyer','middleware'=>'front.wx.shop.auth'],function(){

        Route::get('list',['as'=>'orderIndex','uses'=>'OrderController@index']);
        Route::get('{order_number}',['as'=>'orderDetail','uses'=>'OrderController@detail']);
        Route::get('{order_number}/set_status/{status}',['as'=>'setOrderStatus','uses'=>'OrderController@setStatus']);

    });

    Route::group(['prefix'=>'buyer','namespace'=>'Buyer','middleware'=>'front.wx.shop.auth'],function() {

        Route::get('my/buy',['as'=>'myBuyedShop','uses'=>'OrderController@myBuyed']);
        Route::get('my/browse',['as'=>'myBrowseShop','uses'=>'OrderController@myBrowse']);

    });


    Route::group(['prefix'=>'buyer/favorites','namespace'=>'Buyer'],function(){

        Route::get('/',['as'=>'favoriteIndex','uses'=>'FavoriteController@index']);

    });

    /**
     * buyer rest api
     * @version v1
     * @author zhengqian@dajiayao.cc
     */
    Route::group(['prefix'=>'buyer/rest/v1','namespace'=>'Rest\Buyer\V1'],function(){

        Route::group(['prefix'=>'shop'],function(){

            Route::post('favorite', ['as' => 'favoriteShop', 'uses' => 'ShopController@favorite']);

        });

        Route::group(['prefix'=>'order'],function(){

            Route::post('repay', ['as' => 'repay', 'uses' => 'OrderController@repay']);

        });
    });


    /**
     * restful route
     * @author zhengqian.zhu@dajiayao.cc
     */
    Route::group(['prefix' => 'rest/v1', 'namespace' => 'Rest\V1'], function () {

        //收货地址
        Route::group(['prefix' => 'deliver_address'], function () {
            Route::get('index', ['as' => 'addressIndex', 'uses' => 'AddressController@index']);
            Route::post('create', ['as' => 'addressCreate', 'uses' => 'AddressController@create']);
            Route::post('{id}/destroy', ['as' => 'addressDestroy', 'uses' => 'AddressController@destroy']);
            Route::post('{id}/update', ['as' => 'addressUpdate', 'uses' => 'AddressController@update']);

        });

        //省市区地区
        Route::group(['prefix' => 'region'], function () {
            Route::get('location', ['as' => 'getLocation', 'uses' => 'RegionController@location']);
            Route::get('province/index', ['as' => 'provinceIndex', 'uses' => 'RegionController@provinceIndex']);
            Route::get('city/index', ['as' => 'cityIndex', 'uses' => 'RegionController@cityIndex']);
            Route::get('county/index', ['as' => 'countyIndex', 'uses' => 'RegionController@countyIndex']);
        });

        //店铺详情
        Route::group(['prefix' => 'shop'], function () {
            Route::post('favorite', ['as' => 'favoriteShop', 'uses' => 'ShopController@favorite']);
            Route::get('{shop_id}', ['as' => 'shopDetail', 'uses' => 'ShopController@detail']);
        });

        //订单
        Route::group(['prefix' => 'order'], function () {
            Route::post('create', ['as' => 'orderCreate', 'uses' => 'OrderController@create']);
            Route::post('check', ['as' => 'orderCheck', 'uses' => 'OrderController@check']);
        });

    });
});


