<?php

/**
 * Seller Routes
 * @author Hanxiang
 */
Route::group(['domain' => Config::get('app.domain.seller'), 'namespace'=>'Seller','middleware'=>'front.wx.seller.auth'], function(){

    Route::get('wx-auth', ['as' => 'wxSellerAuth', 'uses' => 'WeixinAuth\WeixinSellerAuthController@auth']);

    Route::get('activate', ['middleware' => 'front.wx.seller.auth', 'as' => 'sellerActivate', 'uses' => 'ShopController@activateS1']);
    Route::post('activate', ['as' => 'sellerActivatePost', 'uses' => 'ShopController@activateS1Post']);

    Route::get('activate2', ['middleware' => 'front.wx.seller.auth', 'as' => 'sellerActivateS2', 'uses' => 'ShopController@activateS2']);

    Route::get('code', ['as' => 'sendSmsCode', 'uses' => 'BaseController@sendSmsCode']);

    Route::get('setshop', ['as' => 'setShop', 'uses' => 'ShopController@setShop']);
    Route::post('setshop', ['as' => 'setShopPost', 'uses' => 'ShopController@setShopPost']);

    Route::group(['prefix' => 'shop'], function(){
        Route::post('image', ['as' => 'postShopImage', 'uses' => 'ShopController@setImagePost']);
    });


    /**
     * @author zhengqian.zhu
     */
    //佣金
    Route::group(['prefix'=>'commission'],function(){
       Route::get('detail',['as'=>'commissionDetail','uses'=>'CommissionController@commissionDetail']);
       Route::get('apply',['as'=>'applyCommission','uses'=>'CommissionController@getApplyDraw']);

    });

    //银行卡
    Route::group(['prefix'=>'bankcard'],function(){
        Route::get('bind',['as'=>'bindBankCard','uses'=>'CommissionController@getBindBankCard']);
        Route::post('bind',['as'=>'postBindBankCard','uses'=>'CommissionController@postBindBankCard']);
        Route::get('modify',['as'=>'modifyBindCard','uses'=>'CommissionController@getModifyBankCard']);
        Route::post('modify',['as'=>'postModifyBankCard','uses'=>'CommissionController@postModifyBankCard']);
    });

    Route::group(['prefix' => 'wx'], function () {
        Route::group(['prefix' => 'v1'], function () {
            Route::get('/', ['as' => 'checkSignature', 'uses' => 'WeixinSellerController@checkSignature']);
            Route::post('/', ['as' => 'WxMsgPost', 'uses' => 'WeixinSellerController@index']);
        });
    });

});

