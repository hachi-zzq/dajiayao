<?php namespace Dajiayao\Http\Controllers\Seller;

use Dajiayao\Model\Seller;
use Dajiayao\Model\Shop;
use Dajiayao\Model\ShopDevice;
use Dajiayao\Model\SmsCode;
use Dajiayao\Services\SellerService;
use Dajiayao\Services\ShopService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class ShopController extends BaseController {

    /**
     * 激活 第一步
     * @author Hanxiang
     * @return $this
     */
    public function activateS1() {
        $sn = Input::get('sn') ? Input::get('sn') : '';
        $shopDevice = ShopDevice::where('device_sn', $sn)->first();
        if (count($shopDevice) && $shopDevice->shop_id) {
            // TODO
            $shop = Shop::find($shopDevice->shop_id);
            return redirect()->route('shopIndex', $shop->short_id);
        }
        return view('seller.activate')
            ->with('config', $this->getJsapiConfig())
            ->with('sn', $sn);
    }

    /**
     * 激活 第一步 POST
     * @author Hanxinag
     */
    public function activateS1Post() {
        $input = Input::all();
        $validator = Validator::make($input, [
            'mobile' => 'required',
            'code' => 'required',
            'sn' => 'required'
        ]);
        if($validator->fails()){
            return redirect()
                ->back()
                ->with('error_tips', $validator->messages()->first());
        }

        // check mobile and code
        $check = SmsCode::checkCode($input['mobile'], $input['code']);
        if (!$check) {
            return redirect()->back()->with('error_tips', "验证码错误");
        }

        // check seller
        $seller = Seller::where('mobile', $input['mobile'])->first();
        if (count($seller) == 0) {
            return redirect()->back()->with('error_tips', "手机号不存在");
        }

        $shop = $seller->shop;
        $shopService = new ShopService();
        $bindResult = $shopService->bindDevice($shop, $input['sn']);
        if ($bindResult) {
            return redirect()->route('sellerActivateS2')
                ->with('success_tips', "绑定成功");
        } else {
            return redirect()->back()->with('error_tips', "绑定失败");
        }
    }

    public function activateS2() {
        $shop = Seller::find($this->sellerId)->shop;
        return view('seller.activate2')
            ->with('shop_id', $shop->id)
            ->with('config', $this->getJsapiConfig());
    }

    /**
     * 设置店铺信息
     * @author Hanxiang
     */
    public function setShop() {

    }

    /**
     * 设置店铺信息 POST
     * @author Hanxiang
     * TODO
     */
    public function setShopPost() {
        $input = Input::all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'name' => 'required',
            'logo' => 'required',
            'banner' => 'required',
            'address' => 'required'
        ]);
        if($validator->fails()){
            return redirect()
                ->back()
                ->with('error_tips', $validator->messages()->first());
        }

        $shop = Shop::find($input['id']);
        if (count($shop) == 0) {
            return redirect()->back()->with('error_tips', "404");
        }

        $shop->name = $input['name'];
        $shop->banner = $input['banner'];
        $shop->thumbnail = $input['logo'];
        $shop->save();
        return redirect()->route('index')->with('success_tips', "success"); //TODO
    }

    /**
     * 上传店铺的图片 logo banner
     * @author Hanxiang
     * TODO
     */
    public function setImagePost() {
        $input = Input::all();
        $validator = Validator::make($input, [
            'shop_id' => 'required',
            'media_id' => 'required',
            'type' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendResponse(20201, $validator->messages()->first());
        }

        if (!in_array($input['type'], ['logo', 'banner'])) {
            return $this->sendResponse(20204, 'Invalid type');
        }

        $sellerService = new SellerService();
        $mediaPath = $sellerService->downloadMedia($input['media_id'], $input['type']);
        if ($mediaPath == false) {
            return $this->sendResponse(20202, 'Invalid media_id');
        }

        $shop = Shop::find($input['shop_id']);
        if (!$shop) {
            return $this->sendResponse(20203, 'Invalid shop_id');
        }
        $shop->thumbnail = $mediaPath;
        $shop->save();

        return $this->sendResponse(10000, 'success', ['url' => url($mediaPath)]);
    }
}
