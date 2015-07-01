<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\Express;
use Dajiayao\Services\ExpressService;
use Dajiayao\Services\PaymentTypeService;
use Dajiayao\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class ExpressController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Haiming
 */
class ExpressController extends Controller
{

    function __construct(ExpressService $expressService)
    {
        $this->expressService = $expressService;
    }

    /**
     * 快递公司 管理首页
     * @author Hanxiang
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $expresses = $this->expressService->getAllExpresses();
        return view('admin.expresses.index')->with('expresses', $expresses);
    }


    public function toAdd()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        return view('admin.expresses.add');
    }

    public function add()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }

        $input = Input::all();
        $validator = Validator::make($input, [
            'sort' => 'required|integer',
            'name' => 'required',
            'code' => 'require',
            'phone' => 'required',
            'website' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        $express = new Express();
        $express->name = $input['name'];
        $express->phone = $input['phone'];
        $express->code = $input['code'];
        $express->sort = $input['sort'];
        $express->website = $input['website'];
        $express->save();

        return redirect()->route('expresses')->with("success_tips", "操作成功！");

    }


    public function toUpdate($id)
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $express = $this->expressService->getExpressById($id);
        if (!$express) {
            \App::abort(404, '没有该快递公司');
        }
        return view('admin.expresses.update')->with('express', $express);
    }


    public function update($id)
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $express = $this->expressService->getExpressById($id);

        if (!$express) {
            \App::abort(404, '没有该快递公司');
        }

        $input = Input::all();
        $validator = Validator::make($input, [
            'sort' => 'required|integer',
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'website' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        $express->name = $input['name'];
        $express->phone = $input['phone'];
        $express->code = $input['code'];
        $express->sort = $input['sort'];
        $express->website = $input['website'];
        $express->save();
        return redirect()->route('expresses')->with("success_tips", "操作成功！");
    }

    public function delete($id)
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $express = $this->expressService->getExpressById($id);

        if (!$express) {
            \App::abort(404, '没有该快递公司');
        }
        $express->delete();
        return redirect()->route('expresses')->with("success_tips", "操作成功！");
    }


}
