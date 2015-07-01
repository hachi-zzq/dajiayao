<?php

/**
 * admin route
 * @author hanxiang@dajiayao.cc
 */
Route::group(['domain' => Config::get('app.domain.oc')], function(){
//    Route::get('/', ['as' => 'index', 'uses' => 'Admin\HomeController@home']);

    Route::get('login', ['as' => 'login', 'uses' => 'UserController@login']);
    Route::post('login', ['as' => 'loginPost', 'uses' => 'UserController@loginPost']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

    Route::group(['middleware' => ['auth']], function(){
        Route::get('/', ['as' => 'adminIndex', 'uses' => 'Admin\HomeController@index']);

        Route::group(['prefix' => 'items'], function(){
            Route::get('/', ['as' => 'adminItems', 'uses' => 'Admin\ItemController@index']);
            Route::get('add', ['as' => 'adminItemsAdd', 'uses' => 'Admin\ItemController@add']);
            Route::post('add', ['as' => 'adminItemsAddPost', 'uses' => 'Admin\ItemController@addPost']);
            Route::get('update/{id}', ['as' => 'adminItemsUpdate', 'uses' => 'Admin\ItemController@update'])->where(['id' => '[0-9]+']);
            Route::post('update/{id}', ['as' => 'adminItemsUpdatePost', 'uses' => 'Admin\ItemController@updatePost'])->where(['id' => '[0-9]+']);
            Route::get('{id}/shelf-status', ['as' => 'adminItemsShelfStatus', 'uses' => 'Admin\ItemController@changeShelfStatus'])->where(['id' => '[0-9]+']);
            Route::post('change-shelf-status', ['as' => 'adminItemsShelfStatusBatch', 'uses' => 'Admin\ItemController@changeShelfStatusBatch']);
        });

        Route::group(['prefix' => 'orders'], function(){
            Route::get('/', ['as' => 'adminOrders', 'uses' => 'Admin\OrderController@index']);
            Route::get('{num}', ['as' => 'adminOrderDetail', 'uses' => 'Admin\OrderController@detail']);
            Route::get('{num}/deliver', ['as' => 'adminOrdersDeliver', 'uses' => 'Admin\OrderController@deliver']);
            Route::post('deliver', ['as' => 'adminOrderDeliverPost', 'uses' => 'Admin\OrderController@deliverPost']);
            Route::post('deliverajax', ['as' => 'deliverAjax', 'uses' => 'Admin\OrderController@deliverAjax']);
            Route::post('updateajax', ['as' => 'updateAjax', 'uses' => 'Admin\OrderController@updateAjax']);
            Route::get('{num}/cancel', ['as' => 'adminOrderCancel', 'uses' => 'Admin\OrderController@cancel']);
        });

        Route::group(['prefix' => 'payment-types'], function(){
            Route::get('/', ['as' => 'paymentTypes', 'uses' => 'Admin\PaymentTypeController@index']);
            Route::get('/{id}/update', ['as' => 'updatePaymentType', 'uses' => 'Admin\PaymentTypeController@toUpdate']);
            Route::post('/{id}/update', ['as' => 'updatePaymentType', 'uses' => 'Admin\PaymentTypeController@update']);
        });

        Route::group(['prefix' => 'settings'], function(){
            Route::get('/', ['as' => 'settings', 'uses' => 'Admin\SettingController@index']);
            Route::post('/update', ['as' => 'updateSetting', 'uses' => 'Admin\SettingController@update']);
        });

        Route::group(['prefix'=>'users'],function(){
            Route::get('/',['as'=>'users','uses'=>'Admin\UserController@index','middleware' => ['auth.admin']]);
            Route::get('/{id}/update',['as'=>'updateUser','uses'=>'Admin\UserController@toUpdate','middleware' => ['auth.admin']])->where(['id' => '[0-9]+']);
            Route::post('/{id}/update',['as'=>'updateUser','uses'=>'Admin\UserController@update','middleware' => ['auth.admin']])->where(['id' => '[0-9]+']);
            Route::any('/{id}/update-status',['as'=>'updateUserStatus','uses'=>'Admin\UserController@updateStatus','middleware' => ['auth.admin']])->where(['id' => '[0-9]+']);
            Route::get('/add',['as'=>'addUser','uses'=>'Admin\UserController@toAdd','middleware' => ['auth.admin']]);
            Route::post('/add',['as'=>'addUser','uses'=>'Admin\UserController@add','middleware' => ['auth.admin']]);

            Route::get('/{id}/update-password',['as'=>'updatePassword','uses'=>'Admin\UserController@toUpdatePassword'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/update-password',['as'=>'updatePassword','uses'=>'Admin\UserController@updatePassword'])->where(['id' => '[0-9]+']);

            Route::any('{id}/manual－login',['as'=>'manualLogin','uses'=>'Admin\UserController@manualLogin','middleware' => ['auth.admin']]);

        });

        Route::group(['prefix' => 'expresses'], function(){
            Route::get('/', ['as' => 'expresses', 'uses' => 'Admin\ExpressController@index']);
            Route::get('add', ['as' => 'addExpress', 'uses' => 'Admin\ExpressController@toAdd']);
            Route::post('add', ['as' => 'addExpress', 'uses' => 'Admin\ExpressController@add']);
            Route::get('update/{id}', ['as' => 'updateExpress', 'uses' => 'Admin\ExpressController@toUpdate'])->where(['id' => '[0-9]+']);
            Route::post('update/{id}', ['as' => 'updateExpress', 'uses' => 'Admin\ExpressController@update'])->where(['id' => '[0-9]+']);
            Route::any('{id}/delete', ['as' => 'deleteExpress', 'uses' => 'Admin\ExpressController@delete'])->where(['id' => '[0-9]+']);
        });


        Route::group(['prefix' => 'sellers'], function(){
            Route::get('/', ['as' => 'sellers', 'uses' => 'Admin\SellerController@index']);
            Route::get('/{id}', ['as' => 'sellerDetail', 'uses' => 'Admin\SellerController@detail'])->where(['id' => '[0-9]+']);
            Route::get('/add', ['as' => 'addSeller', 'uses' => 'Admin\SellerController@add']);
            Route::post('/add', ['as' => 'addSellerPost', 'uses' => 'Admin\SellerController@addPost']);
        });

        Route::group(['prefix' => 'shops'], function(){
            Route::get('/', ['as' => 'shops', 'uses' => 'Admin\ShopController@index']);
            Route::get('/{id}/update', ['as' => 'updateShop', 'uses' => 'Admin\ShopController@toUpdate'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/update', ['as' => 'updateShop', 'uses' => 'Admin\ShopController@update'])->where(['id' => '[0-9]+']);

            Route::get('/{id}/items', ['as' => 'shopItems', 'uses' => 'Admin\ShopController@shopItems'])->where(['id' => '[0-9]+']);
            Route::post('/{id}/items', ['as' => 'addItems', 'uses' => 'Admin\ShopController@addItems'])->where(['id' => '[0-9]+']);
            Route::any('/items/{shopItemId}', ['as' => 'changeShopItemStatus', 'uses' => 'Admin\ShopController@changeShopItemStatus']);
            Route::any('/items/{shopItemId}/delete', ['as' => 'deleteShopItem', 'uses' => 'Admin\ShopController@deleteShopItem']);

        });

        Route::group(['prefix'=>'commission','namespace'=>'Admin'],function(){
            Route::get('applies',['as'=>'applyList','uses'=>'CommissionController@applyList']);
             Route::get('/', ['as' => 'adminCommission', 'uses' => 'Admin\CommissionController@sellerCommissionList']);
            Route::get('/{sellerId}', ['as' => 'adminSellerCommission', 'uses' => 'Admin\CommissionController@sellerCommissionList'])->where(['id' => '[0-9]+']);
        });

    });



    /**
     * 微信接口
     */
    Route::group(array('prefix' => '/wx'), function () {
        Route::group(array('prefix' => 'v1'), function () {
            Route::get('/', array('as' => 'checkSignature', 'uses' => 'WeixinBuyerController@checkSignature'));
        });
    });

    Route::get('/wx/token', array('as' => 'getWeixinToken', 'uses' => 'Rest\V1\WeixinController@getToken'));
    Route::get('/wx/jsapi-ticket', array('as' => 'getWeixinJsAPITicket', 'uses' => 'Rest\V1\WeixinController@getJsAPITicket'));

});