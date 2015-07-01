<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\Image;
use Dajiayao\Model\Item;
use Dajiayao\Model\ItemImage;
use Dajiayao\Model\ItemType;
use Dajiayao\Model\OrderItem;
use Dajiayao\Model\Setting;
use Dajiayao\Model\ShopItem;
use Dajiayao\Model\Supplier;
use Dajiayao\User;
use Auth;
use Illuminate\Support\Facades\Session;
use Input;
use League\Flysystem\Exception;
use Validator;
use J20\Uuid\Uuid;

/**
 * Class ItemController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Hanxiang
 */
class ItemController extends Controller{

    /**
     * 商品管理首页
     * @author Hanxiang
     * @return \Illuminate\View\View
     */
    public function index() {
        $user = Auth::user();
        if ($user->role == User::ROLE_ADMIN) {
            $items = Item::whereRaw('id > 0')->orderBy('updated_at', 'desc')->paginate(20);;
        } else {
            //TODO: supplier_id != user_id
            $items = Item::where('supplier_id', $user->id)->paginate(20);;
        }

        foreach ($items as $item) {
            $item->shopCount = ShopItem::getShopCountByItemID($item->id);
            $item->saleCount = OrderItem::getSaleCountByItemID($item->id);
            $item->todaySaleCount = OrderItem::getTodaySaleCountByItemID($item->id);

            $item->imgurl = asset('/themeforest/images/avatar.png');//TODO
            $itemImage = ItemImage::where('item_id', $item->id)->first();
            if (count($itemImage) > 0) {
                $image = Image::find($itemImage->image_id);
                if (count($image) > 0) {
                    $item->imgurl = $image->url;
                }
            }
        }

        return view('admin.items.index')->with('items', $items);
    }

    /**
     * 增加商品
     * @author Hanxiang
     */
    public function add() {
        $user = Auth::user();
        if ($user->role == User::ROLE_ADMIN) {
            $suppliers = Supplier::all();
        } else {
            // TODO: supplier_id != user_id
            $suppliers = Supplier::where('id', $user->id)->get();
        }

        $itemTypes = ItemType::orderBy('sort')->get();
        $setting = Setting::getByKey(Setting::KEY_COMMISSIONS_RATE);
        return view('admin/items/add')
            ->with('suppliers', $suppliers)
            ->with('itemTypes', $itemTypes)
            ->with('setting', $setting);
    }

    /**
     * 增加商品POST
     * @author Hanxiang
     */
    public function addPost() {
        $input = Input::all();
        $validator = Validator::make($input, [
            'supplier' => 'required',
            'item-title' => 'required',
            'item-code' => 'required',
            'item-barcode' => 'required',
            'item-type' => 'required',
            'item-weight' => 'numeric',
            'item-volume' => 'numeric',
            'item-market-price' => 'required',
            'item-price' => 'required',
            'item-commission-ratio' => 'required',
            'item-commission' => 'required',
            'item-postage-type' => 'required',
            'item-sale-status' => 'required',
            'item-shelf-status' => 'required',
            'item-is-direct-sale' => 'in:Y,N'
        ]);

        if($validator->fails()){
            return redirect()
                ->route('adminItemsAdd')
                ->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        // check supplier
        $supplier = Supplier::find($input['supplier']);
        if (count($supplier) == 0) {
            return redirect()
                ->route('adminItemsAdd')
                ->with('error_tips', "错误： 供应商不存在");
        }

        // upload image
        if (!Input::hasFile('item-image')) {
            return redirect()
                ->route('adminItemsAdd')
                ->with('error_tips', "错误：请上传图片");
        }

        $file = Input::file('item-image');
        $ext = $file->getClientOriginalExtension();
        $filename = Uuid::v4(false) . ".$ext";
        $file->move(public_path('itemimages'), $filename);
        $relPath = "/itemimages/$filename";

        $image = new Image();
        $image->url = $relPath;
        $image->name = $input['item-title'];
        $image->type = 1; //TODO
        $image->save();
        $imageId = $image->id;

        $item = new Item();
        $item->name = $input['item-title'];
        $item->title = $input['item-title'];
        $item->code = $input['item-code'];
        $item->barcode = $input['item-barcode'];
        $item->type_id = $input['item-type'];
        $item->supplier_id = $input['supplier'];
        $item->spec = $input['item-spec'];
        $item->weight = $input['item-weight'];
        $item->volume = $input['item-volume'];
        $item->price = $input['item-price'];
        $item->market_price = $input['item-market-price'];
        $item->stock = $input['item-stock'];
        $item->commission = $input['item-commission'];
        $item->commission_ratio = $input['item-commission-ratio'];
        $item->postage_type = $input['item-postage-type'];
        $item->shelf_status = $input['item-shelf-status'];
        $item->sale_status = $input['item-sale-status'];
        $item->is_direct_sale = $input['item-is-direct-sale'];
        $item->comment = $input['item-comment'];
        try{
            $item->save();
        } catch(\Exception $e) {
            return redirect()
                ->route('adminItemsAdd')
                ->with('error_tips', "添加失败");
        }
        $itemId = $item->id;

        //save item_images
        $itemImages = new ItemImage();
        $itemImages->item_id = $itemId;
        $itemImages->image_id = $imageId;
        $itemImages->save();

        return redirect()
            ->route('adminItems')
            ->with('success_tips', "添加成功");
    }

    /**
     * 编辑商品
     * @author Hanxiang
     * @param $id
     * @return view
     */
    public function update($id) {
        //TODO:检查当前用户是否可编辑

        $item = Item::find($id);
        if (!$item) {
            abort(404);
        }

        $item->imgurl = asset('/themeforest/images/avatar.png');//TODO
        $itemImage = ItemImage::where('item_id', $id)->first();
        if (count($itemImage) > 0) {
            $image = Image::find($itemImage->image_id);
            if (count($image)) {
                $item->imgurl = $image->url;
            }
        }

        $supplier = Supplier::find($item->supplier_id);

        $itemTypes = ItemType::orderBy('sort')->get();
        $setting = Setting::getByKey(Setting::KEY_COMMISSIONS_RATE);
        return view('admin.items.update')
            ->with('item', $item)
            ->with('supplier', $supplier)
            ->with('itemTypes', $itemTypes)
            ->with('setting', $setting);
    }

    /**
     * 编辑商品post
     * @author Hanxiang
     * @param $id
     * @return view
     */
    public function updatePost($id) {
        $item = Item::find($id);
        if (!$item) {
            abort(404);
        }

        $input = Input::all();
        $validator = Validator::make($input, [
            'supplier' => 'required',
            'item-title' => 'required',
            'item-code' => 'required',
            'item-barcode' => 'required',
            'item-stock' => 'required',
            'item-type' => 'required',
            'item-weight' => 'numeric',
            'item-volume' => 'numeric',
            'item-market-price' => 'required',
            'item-price' => 'required',
            'item-commission-ratio' => 'required',
            'item-commission' => 'required',
            'item-postage-type' => 'required',
            'item-sale-status' => 'required',
            'item-shelf-status' => 'required',
            'item-is-direct-sale' => 'in:Y,N'
        ]);

        if($validator->fails()){
            return redirect()
                ->route('adminItemsUpdate', [$id])
                ->with('error_tips', "参数错误: " . $validator->messages()->first());
        }

        if (Input::hasFile('item-image')) {
            $file = Input::file('item-image');
            $ext = $file->getClientOriginalExtension();
            $filename = Uuid::v4(false) . ".$ext";
            $file->move(public_path('itemimages'), $filename);
            $relPath = "/itemimages/$filename";
            $image = new Image();
            $image->url = $relPath;
            $image->name = $input['item-title'];
            $image->type = 1; //TODO
            $image->save();
            $imageId = $image->id;
            $itemImage = ItemImage::where('item_id', $id)->get();
            if (count($itemImage) > 0) {
                ItemImage::where('item_id', $id)->update(['image_id' => $imageId]);
            } else {
                $ii = new ItemImage();
                $ii->item_id = $id;
                $ii->image_id = $imageId;
                $ii->save();
            }
        }

        $item->name = $input['item-title'];
        $item->title = $input['item-title'];
        $item->code = $input['item-code'];
        $item->barcode = $input['item-barcode'];
        $item->type_id = $input['item-type'];
        $item->supplier_id = $input['supplier'];
        $item->spec = $input['item-spec'];
        $item->stock = $input['item-stock'];
        $item->weight = $input['item-weight'];
        $item->volume = $input['item-volume'];
        $item->price = $input['item-price'];
        $item->commission = $input['item-commission'];
        $item->commission_ratio = $input['item-commission-ratio'];
        $item->market_price = $input['item-market-price'];
        $item->postage_type = $input['item-postage-type'];
        $item->shelf_status = $input['item-shelf-status'];
        $item->sale_status = $input['item-sale-status'];
        $item->is_direct_sale = $input['item-is-direct-sale'];
        $item->comment = $input['item-comment'];

        try{
            $item->save();
        } catch(Exception $e) {
            return redirect()
                ->route('adminItemsUpdate', [$id])
                ->with('error_tips', "添加失败");
        }

        return redirect()
            ->route('adminItems')
            ->with('success_tips', "修改成功");
    }

    /**
     * 商品上/下架
     * @author Hanxiang
     * @param $id item_id
     * @param $to status
     * @return view
     */
    public function changeShelfStatus($id) {
        $item = Item::find($id);
        if (!$item) {
            abort(404);
        }
        //Begin modify by minco
        if($item->shelf_status == Item::SHELF_STATUS_YES){
            $item->shelf_status = Item::SHELF_STATUS_NO;
        }else{
            $item->shelf_status = Item::SHELF_STATUS_YES;
        }
        //End modify
        $item->save();
        // TODO:与商品关联的其他状态
        return redirect()
            ->route('adminItems')
            ->with('success_tips', "操作成功");
    }

    /**
     * @author Hanxiang
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeShelfStatusBatch() {
        $input = Input::all();
        if (!isset($input['ids'])) {
            Session::flash('error_tips', "操作失败");
            return response()->json(['result' => 0]);
        }

        if (!is_array($input['ids'])) {
            Session::flash('error_tips', "操作失败");
            return response()->json(['result' => 0]);
        }

        $toStatus = $input['to'];
        if ($toStatus == 1) {
            $s = Item::SALE_STATUS_YES;
        } elseif ($toStatus == 0) {
            $s = Item::SALE_STATUS_NO;
        } else {
            Session::flash('error_tips', "操作失败");
            return response()->json(['result' => 0]);
        }

        Item::whereIn('id', $input['ids'])->update(['sale_status' => $s]);
        Session::flash('success_tips', "操作成功");
        return response()->json(['result' => 1]);
    }

}
