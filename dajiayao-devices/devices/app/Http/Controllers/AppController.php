<?php
namespace Dajiayao\Http\Controllers;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\App;
use Dajiayao\Model\User;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Services\AppService;
use Dajiayao\Services\MpService;
use Dajiayao\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

/**
 * 应用管理
 * Class PageController
 * @package Dajiayao\Http\Controllers
 */
class AppController extends Controller
{

    function __construct(AppService $appService, MpService $mpService, UserService $userService)
    {
        $this->appService = $appService;
        $this->mpService = $mpService;
        $this->userService = $userService;
    }

    public function index()
    {
        $user = Auth::user();
        if($user->role==\Dajiayao\User::ROLE_ADMIN){
            $apps = $this->appService->getApps();
        }else{
            $apps = $this->appService->getAppsByUser($user->id);
        }
        return View::make('admin.apps.index')
            ->with('apps', $apps);
    }

    public function get($id)
    {
        $app = $this->appService->getAppById($id);
        if (!$app) {
            \App::abort(404, '没有该应用');
        }
        $userList = $this->userService->getAllUsers();

        return View::make('admin.apps.detail')
            ->with('app', $app)
            ->with('userList', $userList);
    }

    public function toUpdate($id)
    {
        $app = $this->appService->getAppById($id);
        if (!$app) {
            \App::abort(404, '没有该应用');
        }
        $userList = $this->userService->getAllUsers();

        return View::make('admin.apps.update')
            ->with('app', $app)
            ->with('userList', $userList);
    }

    public function update($id)
    {
        $user = Auth::user();
        if($user->role!=\Dajiayao\User::ROLE_ADMIN){
            \App::abort(503, '没有权限');
        }
        $app = $this->appService->getAppById($id);
        if (!$app) {
            \App::abort(404, '没有该应用');
        }
        $input = Input::only('name', 'app_id', 'app_secret', 'type', 'comment', 'device_url', 'user_id');
        $name = $input['name'];
        $appId = $input['app_id'];
        $appSecret = $input['app_secret'];
        $type = $input['type'];
        $comment = $input['comment'];
        $deviceUrl = $input['device_url'];
        $userId = $input['user_id'];
        if(!$userId){
            $userId = Auth::user()->id;
        }
        if (!($name and $appId and $appSecret and $type)) {
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $app->name = $name;
        $app->app_id = $appId;
        $app->app_secret = $appSecret;
        $app->type = $type;
        $app->comment = $comment;
        $app->device_url = $deviceUrl;
        $app->user_id = $userId;
        $app->save();
        return redirect()->route('apps')->with("success_tips","操作成功！");
    }


    /**
     * 更新状态
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id)
    {
        $user = Auth::user();
        if($user->role!=\Dajiayao\User::ROLE_ADMIN){
            \App::abort(503, '没有权限');
        }
        $app = $this->appService->getAppById($id);
        if (!$app) {
            \App::abort(404, '没有该应用');
        }
        if ($app->status == App::STATUS_LOCKED) {
            $app->status = App::STATUS_NORMAL;
        } else {
            $app->status = App::STATUS_LOCKED;
        }
        $app->save();
        return redirect()->route('apps')->with("success_tips","操作成功！");
    }


    public function toAdd()
    {
        $user = Auth::user();
        if($user->role!=\Dajiayao\User::ROLE_ADMIN){
            \App::abort(400, '没有权限');
        }
        $userList = $this->userService->getAllUsers();
        return View::make('admin/apps/add')
            ->with('userList', $userList)
            ->with('appId', $this->generateAppId())
            ->with('appSecret', $this->generateAppSecret())
            ;
    }

    public function add()
    {
        $input = Input::only('name', 'app_id', 'app_secret', 'type', 'comment', 'device_url', 'user_id');
        $name = $input['name'];
        $appId = $input['app_id'];
        $appSecret = $input['app_secret'];
        $type = $input['type'];
        $comment = $input['comment'];
        $deviceUrl = $input['device_url'];
        $userId = $input['user_id'];
        $user = $this->userService->getUserById($userId);
        if (!($name and $appId and $appSecret and $type and $user)) {
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $app = ['name' => $name,
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'type' => $type,
            'comment' => $comment,
            'device_url' => $deviceUrl,
            'status' => App::STATUS_NORMAL,
            'user_id' => $userId
        ];
        App::create($app);
        return redirect()->route('apps')->with("success_tips","保存成功！");
    }


    public function toUpdateMp($id)
    {
        $app = $this->appService->getAppById($id);
        if (!$app) {
            \App::abort(404, '没有该应用');
        }
        $mp = $this->mpService->getMpByAppId($id);
        if (!$mp) {
            $mp = new WeixinMp();
            $mp->app_id = $id;
        }
        return View::make('admin.apps.update-mp')
            ->with('mp', $mp);
    }

    public function saveOrUpdateMp($app_id)
    {
        $input = Input::only('name', 'appid', 'appsecret', 'comment', 'mp_id');
        $app = $this->appService->getAppById($app_id);
        if (!$app) {
            \App::abort(404, '没有该应用');
        }

        if ($app->type != App::TYPE_WEIXIN) {
            return redirect()->back()->with('error_tips', '该应用类型不是微信')->withInput();
        }
        $name = $input['name'];
        $appId = $input['appid'];
        $appSecret = $input['appsecret'];
        $comment = $input['comment'];
        $mpId = $input['mp_id'];
        if (!($name and $appId and $appSecret and $mpId)) {
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $mp = $this->mpService->getMpByAppId($app_id);
        if (!$mp) {
            $mp = new WeixinMp();
            $mp->app_id = $app_id;
        }
        $mp->name = $name;
        $mp->appid = $appId;
        $mp->appsecret = $appSecret;
        $mp->comment = $comment;
        $mp->app_id = $app_id;
        $mp->mp_id = $mpId;
        $mp->save();
        return redirect()->back()->with("success_tips","操作成功！");
    }

    public function appSecret(){
        return $this->generateAppSecret();
    }

    private function generateAppId()
    {
        return 'yd' . $this->generateHex6Key(16);
    }

    private function generateAppSecret()
    {
        return $this->generateHex6Key(32);
    }

    private function generateHex6Key($num)
    {
        $dicArray = '1234567890abcdef';
        $d = '';
        for ($i = 0; $i < $num; $i++) {
            $d .= $dicArray[rand(0, 15)];
        }
        return $d;
    }
}