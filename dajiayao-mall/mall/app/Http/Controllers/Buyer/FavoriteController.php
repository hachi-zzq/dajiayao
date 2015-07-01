<?php namespace Dajiayao\Http\Controllers\Buyer;

use Dajiayao\Model\FavoriteShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/17
 */
class FavoriteController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function index()
    {
        $buyerId = $this->buyerId;

        $favorites = FavoriteShop::where('buyer_id',$buyerId)->get();

        return view('buyer.favorite.index')->with('favorites',$favorites);
    }

}