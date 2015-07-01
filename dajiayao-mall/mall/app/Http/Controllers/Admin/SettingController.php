<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\Setting;
use Dajiayao\Services\PaymentTypeService;
use Dajiayao\Services\SettingService;
use Dajiayao\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class PaymentTypeController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Haiming
 */
class SettingController extends Controller
{

    function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * 设置 管理首页
     * @author Hanxiang
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $settings = $this->settingService->getAllSetting();
        $map = array();
        foreach ($settings as $setting) {
            $map[$setting->key] = $setting;
        }
        return view('admin.settings.index')->with('items', $map);
    }


    public function update()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }

        $input = Input::only(Setting::KEY_ORDER_PAYMENT_DURATION, Setting::KEY_ORDER_AUTO_RECEIVE_DURATION, Setting::KEY_COMMISSIONS_RATE, Setting::KEY_ORDER_POSTAGE);
        $validator = Validator::make($input, [
            Setting::KEY_ORDER_PAYMENT_DURATION => 'required|integer',
            Setting::KEY_ORDER_AUTO_RECEIVE_DURATION => 'required|integer',
            Setting::KEY_COMMISSIONS_RATE => 'required|numeric',
            Setting::KEY_ORDER_POSTAGE => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        foreach ($input as $key => $value) {
            $this->settingService->updateSetting($key, $value);
        }

        return redirect()->route('settings')->with("success_tips", "操作成功！");
    }


}
