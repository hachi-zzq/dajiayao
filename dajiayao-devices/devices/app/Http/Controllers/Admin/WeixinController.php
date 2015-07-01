<?php namespace Dajiayao\Http\Controllers\Admin;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Services\WeixinService;
use Illuminate\Http\Request;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/26
 */

class WeixinController extends BaseController
{

    public function __construct(WeixinService $weixinService,Request $request)
    {
        $this->weixinService = $weixinService;
        $this->request = $request;
        parent::__construct();
    }

    /**
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function getSyncWeixin()
    {
        $mp = $this->mp;

        return view("admin.system.sync_wx")->with('mps',$mp);
    }


    /**
     * 同步微信数据
     * @return \Illuminate\Http\RedirectResponse
     * @author zhengqian@dajiayao.cc
     */
    public function syncWeixin()
    {
        ini_set('max_execution_time', 300);
        ignore_user_abort(true);

        $mpId = $this->request->get('mp');
        $objMp = WeixinMp::find($mpId);
        if( ! $objMp){
            return redirect(route('adminSyncWeixin'))->with('result', false)->with('msg', '微信号未找到');
        }
        try{
            $count = $this->weixinService->sync($objMp->appid,$objMp->appsecret);
        }catch (\Exception $e){
            return redirect(route('adminSyncWeixin'))->with('result', false)->with('msg', $e->getMessage());
        }

        return redirect(route('adminSyncWeixin'))->with('result', true)->with('msg', sprintf("同步页面 %s 个，设备 %s 个",$count['count_page'],$count['count_device']));


    }
}
