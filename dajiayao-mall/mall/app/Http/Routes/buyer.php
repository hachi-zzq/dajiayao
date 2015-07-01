<?php

Route::group(['domain' => Config::get('app.domain.buyer')], function(){
    Route::group(['prefix' => 'wx'], function () {
        Route::group(['prefix' => 'v1'], function () {
            Route::get('/', ['as' => 'checkSignature', 'uses' => 'WeixinBuyerController@checkSignature']);
            Route::post('/', ['as' => 'WxMsgPost', 'uses' => 'WeixinBuyerController@index']);
        });
    });
});