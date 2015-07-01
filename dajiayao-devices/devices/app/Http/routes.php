<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('redirect/callback',['as'=>'urlRedirectCallBack','uses'=>'SharkController@redirectCallback']);

Route::get('default_page',['as'=>'defaultPage','uses'=>'HomeController@defaultPage']);

//二维码扫描跳转地址
Route::get('qr_url',['as'=>'qrHandlerUrl','uses'=>'QRCodeController@urlHandler']);

Route::get('/', function(){
    return redirect('/admin');
});
Route::get('/qr', function(){
    \PHPQRCode\QRcode::png("http://dev.device.dajiayao.cc/admin/devices?status=0",public_path()."/upload/xxx.png",\PHPQRCode\Constants::QR_ECLEVEL_M,4,0);
});

Route::get('home', 'HomeController@index');
Route::get('currentAvailableDevice', ['as' => 'getCurrentAvailableDevicesAjax', 'uses' => 'Admin\DeviceController@currentAvailableDeviceAjax']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);



Route::get('login', ['as' => 'login', 'uses' => 'UserController@login']);
Route::post('login', ['as' => 'loginPost', 'uses' => 'UserController@loginPost']);
Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function(){
    Route::get('/', ['as' => 'adminHome', 'uses' => 'Admin\HomeController@index']);
    Route::group(['prefix' => 'devices'], function(){
        Route::get('/', ['as' => 'adminDevices', 'uses' => 'Admin\DeviceController@index']);
        Route::post('add', ['as' => 'adminDevicesAdd', 'uses' => 'Admin\DeviceController@addPost']);
        Route::get('alloc', ['as' => 'adminDevicesAlloc', 'uses' => 'Admin\DeviceController@alloc']);
        Route::get('burnin', ['as' => 'adminDevicesBurnin', 'uses' => 'Admin\DeviceController@burnin']);
    });

    /**
     * @微信设备管理
     * @author zhengqian.zhu@dajiayao.cc
     */
    Route::group(['prefix' => 'wx_devices','namespace'=>'Admin'], function(){
        Route::get('/', ['as' => 'adminWxDevicesIndex', 'uses' => 'WxDeviceController@index']);
        Route::post('apply_wx_device', ['as' => 'adminApplyWxDevice', 'uses' => 'WxDeviceController@applyWxDevice']);
        Route::get('bind_page', ['as' => 'adminGetBindPage', 'uses' => 'WxDeviceController@getBindPage']);
        Route::get('{device_id}/page_relation', ['as' => 'adminGetBindPage', 'uses' => 'WxDeviceController@pageRelation']);
        Route::get('{device_id}/update', ['as' => 'adminGetUpdate', 'uses' => 'WxDeviceController@getUpdate']);
        Route::post('update', ['as' => 'adminPostUpdate', 'uses' => 'WxDeviceController@update']);
        Route::post('bind_page', ['as' => 'adminGetBindPages', 'uses' => 'WxDeviceController@bindPages']);
        Route::get('{device_id}/set_redirect', ['as' => 'adminSetRedirect', 'uses' => 'WxDeviceController@setRedirect']);
        Route::post('set_redirect', ['as' => 'adminPostSetRedirect', 'uses' => 'WxDeviceController@doSetRedirect']);
        Route::get('un_redirect', ['as' => 'adminSetUnRedirect', 'uses' => 'WxDeviceController@unRedirect']);
    });

    /**
     * @系统管理
     * @author zhengqian.zhu@dajiayao.cc
     */
    Route::group(['prefix' =>'system','namespace'=>'Admin'], function(){
        Route::get('sync_wx_data', ['as' => 'adminSyncWeixin', 'uses' => 'WeixinController@getSyncWeixin']);
        Route::post('sync_wx_data', ['as' => 'adminPostSyncWeixin', 'uses' => 'WeixinController@syncWeixin']);
    });


    Route::group(['prefix' => 'wxpages'], function(){
        Route::get('/', ['as' => 'adminWxPages', 'uses' => 'Admin\PageController@index']);
        Route::get('add', ['as' => 'adminWxPagesAdd', 'uses' => 'Admin\PageController@add']);
        Route::post('add', ['as' => 'adminWxPagesAddPost', 'uses' => 'Admin\PageController@addPost']);
        Route::get('update/{id}', ['as' => 'adminWxPagesUpdate', 'uses' => 'Admin\PageController@update'])->where(['id' => '[0-9]+']);
        Route::post('update', ['as' => 'adminWxPagesUpdatePost', 'uses' => 'Admin\PageController@updatePost']);
        Route::get('delete/{id}', ['as' => 'adminWxPagesDelete', 'uses' => 'Admin\PageController@delete'])->where(['id' => '[0-9]+']);
        Route::get('bind/{id}', ['as' => 'adminWxPageBind', 'uses' => 'Admin\PageController@bind'])->where(['id' => '[0-9]+']);
        Route::post('bind', ['as' => 'adminWxPageBindPost', 'uses' => 'Admin\PageController@bindPost']);
    });

    Route::get('manufacturersAjax', ['as' => 'adminManufacturersAjax', 'uses' => 'Admin\DeviceController@manufacturersAjax']);
    Route::get('wxdevices', ['as' => 'adminWXdevicesAjax', 'uses' => 'Admin\DeviceController@wxdevicesAjax']);

    Route::group(['prefix'=>'apps'],function(){
        Route::get('/',['as'=>'apps','uses'=>'AppController@index']);
        Route::get('/{id}',['as'=>'getApp','uses'=>'AppController@get'])->where(['id' => '[0-9]+']);
        Route::get('/{id}/update',['as'=>'updateApp','uses'=>'AppController@toUpdate'])->where(['id' => '[0-9]+']);
        Route::post('/{id}/update',['as'=>'updateApp','uses'=>'AppController@update'])->where(['id' => '[0-9]+']);
        Route::any('/{id}/update-status',['as'=>'updateAppStatus','uses'=>'AppController@updateStatus'])->where(['id' => '[0-9]+']);
        Route::get('/add',['as'=>'addApp','uses'=>'AppController@toAdd','middleware' => ['auth.admin']]);
        Route::post('/add',['as'=>'addApp','uses'=>'AppController@add','middleware' => ['auth.admin']]);

        Route::get('/ajax/app-secret',['as'=>'newAppSecret','uses'=>'AppController@appSecret']);

        Route::get('/{id}/mp',['as'=>'updateAppMp','uses'=>'AppController@toUpdateMp'])->where(['id' => '[0-9]+']);
        Route::post('/{id}/mp',['as'=>'updateAppMp','uses'=>'AppController@saveOrUpdateMp'])->where(['id' => '[0-9]+']);

    });

    Route::group(['prefix'=>'manufacturers','middleware' => ['auth.admin']],function(){
        Route::get('/',['as'=>'manufacturers','uses'=>'ManufacturerController@index']);
        Route::get('/{id}/update',['as'=>'updateManufacturer','uses'=>'ManufacturerController@toUpdate'])->where(['id' => '[0-9]+']);
        Route::post('/{id}/update',['as'=>'updateManufacturer','uses'=>'ManufacturerController@update'])->where(['id' => '[0-9]+']);
        Route::get('/add',['as'=>'addManufacturer','uses'=>'ManufacturerController@toAdd']);
        Route::post('/add',['as'=>'addManufacturer','uses'=>'ManufacturerController@add']);
    });

    Route::group(['prefix'=>'device-models','middleware' => ['auth.admin']],function(){
        Route::get('/',['as'=>'deviceModels','uses'=>'DeviceModelController@index']);
        Route::get('/{id}/update',['as'=>'updateDeviceModel','uses'=>'DeviceModelController@toUpdate'])->where(['id' => '[0-9]+']);
        Route::post('/{id}/update',['as'=>'updateDeviceModel','uses'=>'DeviceModelController@update'])->where(['id' => '[0-9]+']);
        Route::get('/add',['as'=>'addDeviceModel','uses'=>'DeviceModelController@toAdd']);
        Route::post('/add',['as'=>'addDeviceModel','uses'=>'DeviceModelController@add']);
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


});
Route::post('genpdf', ['as' => 'genPDF', 'uses' => 'Admin\DeviceController@genPDF']);

/**
 * restful route
 * @author zhengqian.zhu@dajiayao.cc
 */
Route::group(['prefix'=>'rest/v1','namespace'=>'Rest\V1'],function(){

    Route::group(['prefix'=>'weixin/device','middleware'=>'rest.token'],function(){
        Route::get('/',['as'=>'devicesIndex','uses'=>'DeviceController@index']);
        Route::get('{sn}',['as'=>'getDevBySn','uses'=>'DeviceController@getDevBySn']);
        Route::post('{sn}/bind_page',['as'=>'deviceBindPage','uses'=>'DeviceController@bindPage']);
        Route::post('{sn}/location',['as'=>'setLocation','uses'=>'DeviceController@setLocation']);
        Route::post('{sn}/update_comment',['as'=>'udpateComment','uses'=>'DeviceController@updateComment']);

    });

    Route::group(['prefix'=>'weixin/page','middleware'=>'rest.token'],function(){

        Route::get('{page_id}',['as'=>'pageInfo','uses'=>'PageController@getInfo']);
        Route::post('create',['as'=>'pageCreate','uses'=>'PageController@create']);
        Route::post('{page_id}/update',['as'=>'pageUpdate','uses'=>'PageController@update']);
        Route::post('delete',['as'=>'pageDelete','uses'=>'PageController@delete']);

        Route::post('{page_id}/bind_device',['as'=>'pageBindDevice','uses'=>'PageController@bindDevice']);

    });


    Route::group(['prefix'=>'weixin/statistics','middleware'=>'rest.token'],function(){

        Route::get('device',['as'=>'deviceStatistics','uses'=>'StatisticsController@deviceStatistics']);
        Route::get('page',['as'=>'pageStatistics','uses'=>'StatisticsController@pageStatistics']);


    });

    Route::get('weixin/shakaround/shakeinfo',['middleware'=>'rest.token','as'=>'getSharkInfo','uses'=>'SharkController@getInfo']);

    Route::get('token',[
        'as'=>'getToken',
        'uses'=>'AuthController@getToken'
    ]);

});
