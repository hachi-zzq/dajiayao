<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\Seller;
use Dajiayao\Model\Shop;
use Dajiayao\Model\WxUser;
use Dajiayao\Services\PaymentTypeService;
use Dajiayao\Services\SellerService;
use Dajiayao\Services\ShopService;
use Dajiayao\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use J20\Uuid\Uuid;

/**
 * Class SellerController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Haiming
 */
class SellerController extends Controller
{

    function __construct(SellerService $sellerService)
    {
        $this->sellerService = $sellerService;
    }

    /**
     * 卖家 管理首页
     * @author Hanxiang
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            return "Not have access right.";
        }
        $sellers = $this->sellerService->getAllSellers(20);
        return view('admin.sellers.index')->with('sellers', $sellers);
    }

    public function detail($id)
    {
        $seller = $this->sellerService->getSellerById($id);
        if (!$seller) {
            abort(404);
        }
        return view('admin.sellers.detail')->with('seller', $seller);
    }

    public function toUpdate($id)
    {

    }


    public function update($id)
    {

    }

    public function add() {
        return view('admin.sellers.add');
    }

    public function addPost() {
        $input = Input::all();
        $validator = Validator::make($input, [
            'mobile' => 'required',
            'realname' => 'required'
        ]);
        if($validator->fails()){
            return redirect()
                ->back()
                ->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        $mobile = trim($input['mobile']);
        $realname = trim($input['realname']);
        $sellerByMobile = Seller::where('mobile', $mobile)->first();
        if (count($sellerByMobile) > 0) {
            return redirect()
                ->back()
                ->with('error_tips', "手机号已存在")
                ->withInput();
        }

        // add weixin user
//        $wxUser = new WxUser();
//        $wxUser->subscribe = 1;
//        $wxUser->openid = Uuid::v4(false); //TODO
//        $wxUser->nickname = $input['nickname'];
//        $wxUser->sex = 1;
//        $wxUser->city = '苏州';
//        $wxUser->country = '中国';
//        $wxUser->province = '江苏';
//        $wxUser->language = 'zh_CN';
//        $wxUser->headimgurl = '';
//        $wxUser->role = WxUser::ROLE_SELLER;
//        $wxUser->status = 1;
//        $wxUser->save();
//        $wxUserId = $wxUser->id;

        // add seller
        $seller = new Seller();
        //$seller->wx_user_id = $wxUserId;
        $seller->mobile = $mobile;
        $seller->status = 1;
        $seller->parent_id = 0;
        $seller->realname = $realname;
        $seller->save();

        // add shop
        $shopService = new ShopService();
        $shopService->createShop(
            $seller,
            Shop::TYPE_DIRECT,
            Shop::MODE_NORMAL,
            '丫摇小店',
            '丫摇小店',
            '',
            'images/logo_160.png',
            '/images/banner_'.rand(1,9).'.jpg'
        );

        return redirect()->route('sellers')->with('success_tips', "操作成功");
    }


}
