<?php namespace Dajiayao\Services;

use Dajiayao\Library\Weixin\Mp;
use \Dajiayao\Model\WeixinMp;
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/13
 */

class MpService extends BaseController
{

    /**
     * @param Mp $mp 微信实体
     * @return mixed
     */
    public function create(Mp $mp)
    {
        $wx = new WeixinMp();
        foreach ($mp as $k=>$attr) {
            $wx->$k = $attr;
        }

        $wx->status = 1;
        $wx->save();

        return $wx->id;
    }

    /**
     * @param WeixinMp $wxmp 要修改的实体
     * @param Mp $mp 微信实体
     * @return bool
     */
    public function update(WeixinMp $wxmp,Mp $mp)
    {

        foreach ($mp as $k=>$attr) {
            $wxmp->$k = $attr;
        }

        return $wxmp->save();

    }


    /**
     * 删除微信公众账号
     * @param WeixinMp $mp
     * @return mixed
     */
    public function delete(WeixinMp $wxmp)
    {
        return $wxmp->delete();
    }

    /**
     * 获得所有公众号
     * @return mixed
     */
    public function getMps(){
        return WeixinMp::all();
    }

    public function getMpById($id){
        return WeixinMp::where('id', $id)->first();

    }

    public function getMpByAppId($appId){
        return WeixinMp::where('app_id', $appId)->first();

    }
}