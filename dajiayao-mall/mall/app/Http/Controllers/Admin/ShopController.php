<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\Item;
use Dajiayao\Model\Shop;
use Dajiayao\Model\ShopItem;
use Dajiayao\Services\ItemService;
use Dajiayao\Services\PaymentTypeService;
use Dajiayao\Services\SellerService;
use Dajiayao\Services\ShopService;
use Dajiayao\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use J20\Uuid\Uuid;

/**
 * Class ShopController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Haiming
 */
class ShopController extends Controller
{

    function __construct(ShopService $shopService, ItemService $itemService)
    {
        $this->shopService = $shopService;
        $this->itemService = $itemService;
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
        $shops = $this->shopService->getAllShop(20);
        return view('admin.shops.index')->with('shops', $shops);
    }

    public function toUpdate($id)
    {
        $shop = $this->shopService->getShopById($id);
        if (!$shop) {
            abort(404);
        }
        return view('admin.shops.update')->with('shop', $shop);
    }

    public function update($id)
    {
        $relPath = null;
        $shop = $this->shopService->getShopById($id);
        if (!$shop) {
            abort(404);
        }

        $input = Input::all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('error_tips', "参数错误: " . $validator->messages()->first());
        }
        if (Input::hasFile('image')) {
            $file = Input::file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = Uuid::v4(false) . ".$ext";
            $file->move(public_path('shopimages'), $filename);
            $relPath = "/shopimages/$filename";
        }
        if(!$relPath){
            $relPath = '/images/logo_160.png';
        }

        $shop->name = $input['name'];
        $shop->thumbnail = $relPath;
        $shop->comment = $input['comment'];
        $shop->subtitle = $shop->name;

        try {
            $shop->save();
            if ($shop->page_id) {
                $this->shopService->updateShopPage($shop);
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error_tips', "修改失败");
        }

        return redirect()
            ->route('shops')
            ->with('success_tips', "修改成功");
    }


    public function shopItems($id)
    {
        $shop = $this->shopService->getShopById($id);
        if (!$shop) {
            abort(404);
        }

        $shopItems = $this->shopService->getShopItems($id);

        return view('admin.shops.shop-items')
            ->with('shop', $shop)
            ->with('shopItems', $shopItems);
    }

    public function addItems($shopId)
    {
        $input = Input::only('item-code');
        $itemCode = $input['item-code'];
        $shop = $this->shopService->getShopById($shopId);

        if (!$shop) {
            abort(404);
        }
        $item = $this->itemService->getItemsByCode($itemCode);
        if (!$item
            or $item->shelf_status == Item::SHELF_STATUS_NO
            or $this->shopService->checkShopItemExist($shopId, $item->id)
        ) {
            return redirect()
                ->back()
                ->with('error_tips', "增加失败");
        }

        if($shop->is_direct_sale!=Shop::IS_DIRECT_SALE_YES and $item->is_direct_sale==Item::IS_DIRECT_SALE_YES){
            return redirect()
                ->back()
                ->with('error_tips', "增加失败：该商品为官方直营商品不允许上架。");
        }
        $shopItem = new ShopItem();
        $shopItem->shop_id = $shopId;
        $shopItem->item_id = $item->id;
        $shopItem->status = ShopItem::STATUS_YES;
        $shopItem->stock = 0; //TODO
        $shopItem->sort = 1; //TODO
        $shopItem->save();
        return redirect()->back()
            ->with('success_tips', "增加成功");
    }

    public function changeShopItemStatus($shopItemId)
    {
        $shopItem = $this->shopService->getShopItem($shopItemId);
        if (!$shopItem) {
            abort(404);
        }

        if ($shopItem->status == ShopItem::STATUS_YES) {
            $shopItem->status = ShopItem::STATUS_NO;
        } else {
            $shopItem->status = ShopItem::STATUS_YES;
        }
        $shopItem->save();
        return redirect()->back()
            ->with('success_tips', "操作");
    }

    public function deleteShopItem($shopItemId)
    {
        $shopItem = $this->shopService->getShopItem($shopItemId);
        if (!$shopItem) {
            abort(404);
        }
        $shopItem->delete();
        return redirect()->back()
            ->with('success_tips', "操作");
    }

}
