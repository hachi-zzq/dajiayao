<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\App;
use Dajiayao\Model\Device;
use Dajiayao\Model\DeviceApp;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\WeixinPage;
use Dajiayao\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    public function index() {
        $user = Auth::user();
        if ($user->role == User::ROLE_ADMIN) {
            $devicesCount = Device::all()->count();
            $wxDevicesCount = WeixinDevice::all()->count();
            $appsCount = App::all()->count();
            $wxPagesCount = WeixinPage::all()->count();
        } else {
            $apps = App::where('user_id',$user->id)->lists('id');
            $devicesCount = DeviceApp::whereIn('app_id',$apps)->count();
            $mps = WeixinMp::whereIn('app_id',$apps)->lists('id');
            $wxDevicesCount = WeixinDevice::whereIn('wx_mp_id',$mps)->count();
            $appsCount = count($apps);
            $wxPagesCount = WeixinPage::whereIn('wx_mp_id',$mps)->count();
        }

        return view('admin.index')
            ->with('devicesCount', $devicesCount)
            ->with('wxDevicesCount', $wxDevicesCount)
            ->with('appsCount', $appsCount)
            ->with('wxPagesCount', $wxPagesCount);
    }

}
