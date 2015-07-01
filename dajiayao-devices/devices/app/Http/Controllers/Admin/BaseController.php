<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use \Auth;
use \DB;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/19
 */
class BaseController extends Controller
{

    protected $mp = [];
    protected $app = [];


    public function __construct()
    {
        $userId = Auth::user()->id;
        //当前角色下的所有app
        $arrApp = DB::table('apps')->where('user_id',$userId)->lists('id');


        //当前角色下所有的mp
        $arrMp = DB::table('wx_mp')->whereIn('app_id',$arrApp)->lists('id');

        if(Auth::user()->role == 1){
            $arrApp = DB::table('apps')->lists('id');
            $arrMp = DB::table('wx_mp')->lists('id');
        }

        $this->mp = $arrMp;
        $this->app = $arrApp;
    }
}
