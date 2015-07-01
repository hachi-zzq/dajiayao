<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Services\PaymentTypeService;
use Dajiayao\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class PaymentTypeController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Haiming
 */
class PaymentTypeController extends Controller
{

    function __construct(PaymentTypeService $paymentTypeService)
    {
        $this->paymentService = $paymentTypeService;
    }

    /**
     * 支付类型 管理首页
     * @author Hanxiang
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $types = $this->paymentService->getAllPaymentTypes();
        return view('admin.payment-types.index')->with('paymentTypes', $types);
    }


    public function toUpdate($id)
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $type = $this->paymentService->getPaymentTypeById($id);
        if (!$type) {
            \App::abort(404, '没有该支付方式');
        }
        return view('admin.payment-types.update')->with('paymentType', $type);
    }


    public function update($id)
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $type = $this->paymentService->getPaymentTypeById($id);

        if (!$type) {
            \App::abort(404, '没有该支付方式');
        }

        $input = Input::all();
        $validator = Validator::make($input, [
            'sort' => 'required|integer',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        $type->status = $input['status'];
        $type->sort = $input['sort'];
        $type->save();
        return redirect()->route('paymentTypes')->with("success_tips","操作成功！");
    }


}
