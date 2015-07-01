<?php namespace Dajiayao\Http\Controllers\WeixinAuth;

use Illuminate\Http\Request;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/4
 */
class WeixinSellerAuthController extends WeixinAuthController
{

    public function __construct(Request $request)
    {
        $this->type = 'seller';
        parent::__construct($request);
    }


    public function auth()
    {
        return parent::auth();
    }
}