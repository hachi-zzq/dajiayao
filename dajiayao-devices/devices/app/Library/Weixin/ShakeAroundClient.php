<?php
/**
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/5/6
 * Time: 9:40
 */

namespace Dajiayao\Library\Weixin;


use Dajiayao\Library\Help\ClientHelp;

class ShakeAroundClient extends WeixinClient
{

    const APPLY_DEVICE_ID = "https://api.weixin.qq.com/shakearound/device/applyid?access_token=%s";
    const UPDATE_DEVICE = "https://api.weixin.qq.com/shakearound/device/update?access_token=%s";
    const BIND_LOCATION = "https://api.weixin.qq.com/shakearound/device/bindlocation?access_token=%s";
    const SEARCH_DEVICE = "https://api.weixin.qq.com/shakearound/device/search?access_token=%s";
    const ADD_PAGE = "https://api.weixin.qq.com/shakearound/page/add?access_token=%s";
    const UPDATE_PAGE = "https://api.weixin.qq.com/shakearound/page/update?access_token=%s";
    const SEARCH_PAGE = "https://api.weixin.qq.com/shakearound/page/search?access_token=%s";
    const DELETE_PAGE = "https://api.weixin.qq.com/shakearound/page/delete?access_token=%s";
    const ADD_MATERIAL = "https://api.weixin.qq.com/shakearound/material/add?access_token=%s";
    const BIND_PAGE = "https://api.weixin.qq.com/shakearound/device/bindpage?access_token=%s";
    const GET_SHAKE_INFO = "https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token=%s";
    const STATISTICS_DEVICE = "https://api.weixin.qq.com/shakearound/statistics/device?access_token=%s";
    const STATISTICS_PAGE = "https://api.weixin.qq.com/shakearound/statistics/page?access_token=%s";



    /**
     * 申请设备ID
     * @param $access_token
     * @return string
     */
    public function applyDeviceId($quantity, $apply_reason, $comment, $poiId, $access_token)
    {
        $postData = array("quantity" => $quantity, "apply_reason" => $apply_reason);
        if ($comment) {
            $postData['comment'] = $comment;
        }
        if ($poiId) {
            $postData['poi_id'] = $poiId;
        }

        $url = sprintf(self::APPLY_DEVICE_ID, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 更新设备备注
     * @param $comment
     * @param DeviceIdentifier $deviceIdentifier
     * @param $access_token
     * @return string
     */
    public function updateDeviceComment($comment, DeviceIdentifier $deviceIdentifier, $access_token)
    {
        $postData = array();
        $postData['device_identifier'] = $deviceIdentifier;
        $postData['comment'] = $comment;
        $url = sprintf(self::UPDATE_DEVICE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 绑定门店
     * @param $poiId 门店ID
     * @param DeviceIdentifier $deviceIdentifier
     * @param $access_token
     * @return string
     */
    public function bindLocation($poiId, DeviceIdentifier $deviceIdentifier, $access_token)
    {
        $postData = array();
        $postData['device_identifier'] = json_encode($deviceIdentifier);
        $postData['poi_id'] = $poiId;
        $url = sprintf(self::BIND_LOCATION, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 查询指定设备
     * @param DeviceIdentifier $deviceIdentifier
     * @param $access_token
     * @return string
     */
    public function searchDeviceByDeviceIdentifier(DeviceIdentifier $deviceIdentifier, $access_token)
    {
        $postData = array();
        $postData['device_identifiers'] = array();
        array_push($postData['device_identifiers'], json_encode($deviceIdentifier));
        $url = sprintf(self::SEARCH_DEVICE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 查询指定批次的设备信息
     * @param $applyId 申请批次ID
     * @param $begin
     * @param $count
     * @param $access_token
     * @return string
     */
    public function searchDeviceByApplyId($applyId, $begin, $count, $access_token)
    {
        $postData = array();
        $postData['apply_id'] = $applyId;
        $postData['begin'] = $begin;
        $postData['count'] = $count;
        $url = sprintf(self::SEARCH_DEVICE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 指定范围查询设备信息
     * @param $begin
     * @param $count
     * @param $access_token
     * @return string
     */
    public function searchDeviceByRange($begin, $count, $access_token)
    {
        $postData = array();
        $postData['begin'] = $begin;
        $postData['count'] = $count;
        $url = sprintf(self::SEARCH_DEVICE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 新增页面
     * @param Page $page
     * @param $access_token
     * @return string
     */
    public function addPage(Page $page, $access_token)
    {
//        $postData = json_encode($page);
//        unset($postData['page_id']);
        $url = sprintf(self::ADD_PAGE, $access_token);
        return $this->doPost($url, json_encode($page));
    }

    /**
     * 编辑页面
     * @param Page $page
     * @param $access_token
     * @return string
     */
    public function updatePage(Page $page, $access_token)
    {
        $url = sprintf(self::UPDATE_PAGE, $access_token);
        return $this->doPost($url, json_encode($page));
    }

    /**
     * 查询页面信息
     * @param array $ids 页面id数组
     * @param $access_token
     * @return string
     */
    public function searchPageByIds(array $ids, $access_token)
    {
        $postData = array();
        $postData["page_ids"]=json_encode($ids);
        $url = sprintf(self::SEARCH_PAGE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 指定范围查询页面信息
     * @param $begin 开始索引
     * @param $count 查询个数
     * @param $access_token
     * @return string
     */
    public function searchPageByRange($begin, $count, $access_token)
    {
        $postData = array();
        $postData['begin'] = $begin;
        $postData['count'] = $count;
        $url = sprintf(self::SEARCH_PAGE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 删除页面
     * @param array $ids 页面id数组
     * @param $count
     * @param $access_token
     * @return string
     */
    public function deletePageByIds(array $ids, $access_token)
    {
        $url = sprintf(self::DELETE_PAGE, $access_token);
        return $this->doPost($url, json_encode($ids));
    }


    /**
     * 增加素材 @TODO
     * @param $materialFile
     * 素材地址：local:// 本地   url:// 远程地址
     * @param $access_token
     * @return string 素材在微信服务器上的URL
     */
    public function addMaterial($materialFile, $access_token)
    {

        $ret = ClientHelp::getCurl(sprintf(self::ADD_MATERIAL,$access_token),'post',['file'=>new \CURLFile($materialFile)]);
        if($ret->errno != 0){
            throw new \Exception($ret->error,$ret->errno);
        }
        return json_decode($ret->content);
    }

    /**
     * 设备绑定页面
     * @param DeviceIdentifier $deviceIdentifier 设备信息
     * @param array $pageIds 页面ID数组
     * @param int $bind 关联操作标志位， 0为解除关联关系，1为建立关联关系
     * @param int $append 新增操作标志位， 0为覆盖，1为新增
     * @param $access_token
     * @return string
     */
    public function bindPage(DeviceIdentifier $deviceIdentifier, array $pageIds, $bind = 0, $append = 0, $access_token)
    {
        $postData = array();
        $postData['device_identifier'] = $deviceIdentifier;
        $postData["page_ids"] = $pageIds;
        $postData['bind'] = $bind;
        $postData['append']  = $append;
         $url = sprintf(self::BIND_PAGE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 获取摇周边的设备及用户信息
     * @param $ticket 摇周边业务的ticket，可在摇到的URL中得到，ticket生效时间为30分钟，每一次摇都会重新生成新的ticket
     * @param int $needPoi 是否需要返回门店poi_id，传1则返回，否则不返回
     * @param $access_token
     * @return string
     */
    public function getShakeInfo($ticket, $needPoi = 1, $access_token)
    {
        $postData = array();
        $postData['ticket'] = $ticket;
        $postData['need_poi'] = $needPoi;
        $url = sprintf(self::GET_SHAKE_INFO, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 以设备为维度的数据统计接口
     * @param DeviceIdentifier $deviceIdentifier
     * @param $begin_date
     * @param $end_date
     * @param $access_token
     * @return string
     */
    public function statisticsDevice(DeviceIdentifier $deviceIdentifier, $begin_date, $end_date, $access_token)
    {
        $postData = array();
        $postData['device_identifier'] = $deviceIdentifier;
        $postData['begin_date'] = $begin_date;
        $postData['end_date'] = $end_date;
        $url = sprintf(self::STATISTICS_DEVICE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }

    /**
     * 以页面为维度的数据统计接口
     * @param $page_id
     * @param $begin_date
     * @param $end_date
     * @param $access_token
     * @return string
     */
    public function statisticsPage($page_id, $begin_date, $end_date, $access_token)
    {
        $postData = array();
        $postData['page_id'] = $page_id;
        $postData['begin_date'] = $begin_date;
        $postData['end_date'] = $end_date;
        $url = sprintf(self::SEARCH_PAGE, $access_token);
        return $this->doPost($url, json_encode($postData));
    }



}