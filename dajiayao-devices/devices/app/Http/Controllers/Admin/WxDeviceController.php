<?php namespace Dajiayao\Http\Controllers\Admin;
use Dajiayao\Library\Weixin\DeviceIdentifier;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\App;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\User;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\WeixinPage;
use Dajiayao\Services\DeviceService;
use \Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use \DB;

/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/5/19
 */

class WxDeviceController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 设备列表
     * 设备列表
     * @param DeviceService $deviceService
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function index(Request $request,DeviceService $deviceService)
    {

        $inputData = $request->all();

        $arrMp = $this->mp;

        $wxDevices = WeixinDevice::whereIn('wx_mp_id',$arrMp);

        //过滤微信号
        if(isset($inputData['wx_mp_id']) && $inputData['wx_mp_id'] != 0){
            $wxDevices = $wxDevices->where('wx_mp_id',$inputData['wx_mp_id']);
        }

        //过滤绑定条件
        if(isset($inputData['bind']) && $inputData['bind'] == 1){
            $wxDevices = $wxDevices->whereIn('id',DB::table('device_page')->lists('wx_device_id'));
        }elseif(isset($inputData['bind']) && $inputData['bind']== '-1'){
            $wxDevices = $wxDevices->whereNotIn('id',DB::table('device_page')->lists('wx_device_id'));
        }

//        //过滤sn
//        if(isset($inputData['sn']) && $inputData['sn'] != ''){
//           $sn =  DB::table('devices')->where('sn','like',"%".$inputData['sn']."%")->lists('wx_device_id');
//            $wxDevices = $wxDevices->whereIn('id',$sn);
//        }
//
//        //过滤minor
//        if(isset($inputData['minor']) && $inputData['minor'] != ''){
//            $wxDevices = $wxDevices->where('minor',$inputData['minor']);
//        }

        if(isset($inputData['kw']) && $inputData['kw'] != '') {
            $kw = $inputData['kw'];
            $ids =  DB::table('devices')->where('sn','like',"%".$kw."%")->lists('wx_device_id');
            $wxDevices = $wxDevices->where(function ($query) use ($kw,$ids) {
                $query->where('uuid'  , 'like', "%".$kw."%")
                    ->orwhere('device_id', 'like', "%".$kw."%")
                    ->orwhere('major', 'like', "%".$kw."%")
                    ->orwhere('minor', 'like', "%".$kw."%")
                    ->orwhere('comment', 'like', "%".$kw."%")
                    ->orwhereIn('id',$ids);
            });
        }
        $wxDevices = $wxDevices->paginate(20);

        //取得是否烧号？烧号后显示出sn
        foreach($wxDevices as $d){
            $d->sn = Device::where('wx_device_id',$d->id)->get();
            $d->devPage = DevicePage::where('wx_device_id',$d->id)->get()->toArray();

        }



        return view('admin.wx_device.index')->with('wx_devices',$wxDevices)
                                            ->with('wx_mp_id',isset($inputData['wx_mp_id']) ? $inputData['wx_mp_id'] : 0)
                                            ->with('bind',isset($inputData['bind']) ? $inputData['bind'] : 0)
                                            ->with('kw',isset($inputData['kw']) ? $inputData['kw'] : '')
                                            ->with('mps',$arrMp);
    }

    /**
     * 申请设备
     * @param DeviceService $deviceService
     * @author zhengqian@dajiayao.cc
     */
    public function applyWxDevice(ShakeAroundClient $shakeAroundClient,DeviceService $deviceService)
    {
        $inputData = Input::only('wx_mp_id','sum');

        $validator = Validator::make($inputData,[
           'wx_mp_id'=>'required',
            'sum'=>'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                'msgcode'=>-1,
                'message'=>$validator->messages()->first()
            ]);
        }

        $mpId = $inputData['wx_mp_id'];

        $mp = WeixinMp::find($mpId);

        if( ! $mp){
            return response()->json([
                'msgcode'=>-2,
                'message'=>'微信号未找到'
            ]);
        }

        if($inputData['sum'] > 100){
            return response()->json([
                'msgcode'=>-3,
                'message'=>'一次最多申请100个'
            ]);
        }

        try{
            $ret = $deviceService->applyDeviceOnline($shakeAroundClient,(int)$inputData['sum'],'测试','comment',0,$mp->appid,$mp->appsecret);

        }catch (\Exception $e){
            return response()->json([
               'msgcode'=>$e->getCode(),
                'message'=>$e->getMessage()
            ]);
        }

        $applyId = $ret->apply_id;
        $devices = $ret->device_identifiers;

        foreach($devices as $dev){
            $d = new WeixinDevice();
            $d->uuid = $dev->uuid;
            $d->major = $dev->major;
            $d->minor = $dev->minor;
            $d->device_id = $dev->device_id;
            $d->apply_id = $applyId;
            $d->wx_mp_id = $mpId;
            $d->save();
        }

        return redirect(route('adminWxDevicesIndex'))->with('result', true)->with('msg', "操作成功");


    }

    /**
     * @param null $wxDeviceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @author zhengqian@dajiayao.cc
     */
    public function pageRelation($wxDeviceId=null)
    {
        if( ! $wxDeviceId){
            return redirect(route('adminWxDevicesIndex'))->with('result', false)->with('msg', "id 不存在");
        }


//        $arrMp = $this->mp;
        $pageIds = DB::table('device_page')->where("wx_device_id",$wxDeviceId)->lists('wx_page_id');
        $mp = WeixinDevice::find($wxDeviceId)->mp;
        $pages = WeixinPage::where('wx_mp_id',$mp->id)->whereNotIn('id',$pageIds)->get();
        $bindPage = DevicePage::where('wx_device_id',$wxDeviceId)->get();


        return view('admin.wx_device.relation_page')->with('bind_pages',$bindPage)->with('pages',$pages)->with('device_id',$wxDeviceId);

    }

    /**
     * ajax 绑定页面
     * @author zhengqian@dajiayao.cc
     */
    public function getBindPage(DeviceService $deviceService,ShakeAroundClient $shakeAroundClient)
    {
        $inputData = Input::only("page_id",'device_id','flag');
        if( ! $inputData['page_id']){
            return response()->json([
                'msgcode'=>-1,
                'message'=>'请选择页面'
            ]);
        }

        if( ! $inputData['device_id']){
            return response()->json([
                'msgcode'=>-2,
                'message'=>'请选择设备'
            ]);
        }

        $objDev = WeixinDevice::find($inputData['device_id']);
        try{
            if($inputData['flag'] == 1){
                $deviceService->bindPage($shakeAroundClient,$objDev,[$inputData['page_id']],1,1,$objDev->mp->appid,$objDev->mp->appsecret);

            }elseif($inputData['flag'] == 0){
                $deviceService->bindPage($shakeAroundClient,$objDev,[$inputData['page_id']],0,1,$objDev->mp->appid,$objDev->mp->appsecret);
            }
        }catch (\Exception $e){
            return response()->json([
                'msgcode'=>$e->getCode(),
                'message'=>$e->getMessage()
            ]);
        }

        return response()->json([
            'msgcode'=>0,
            'message'=>'操作成功'
        ]);

    }

    /**
     * 绑定页面
     * @param DeviceService $deviceService
     * @param $wxDeviceId
     * @param $pageIds
     * @param int $flage
     * @author zhengqian@dajiayao.cc
     */
    public function bindPages(ShakeAroundClient $shakeAroundClient,DeviceService $deviceService)
    {
        $inputData = Input::all();

        $wxDeviceId = $inputData['device_id'];

        $objDev = WeixinDevice::find($wxDeviceId);
        if ( ! $objDev){
            return redirect(route('adminGetBindPage',[$wxDeviceId]))->with('result', false)->with('msg', "设备id 不存在");
        }

        $pageIds = isset($inputData['page_id']) ? $inputData['page_id'] : [];
        foreach($pageIds as $id){
            $objPage = WeixinPage::find($id);
            if( ! $objPage){
                return redirect(route('adminGetBindPage',[$wxDeviceId]))->with('result', false)->with('msg', sprintf("页面id：%s 不存在",$id));
            }
        }

        //如果空page_ids，则解绑所有页面
        if(empty($pageIds)){
            $dp = DB::table('device_page')->where('wx_device_id',$wxDeviceId)->lists('wx_page_id');
            try{
                $deviceService->bindPage($shakeAroundClient,$objDev,$dp,0,0,$objDev->mp->appid,$objDev->mp->appsecret);
            }catch (\Exception $e){
                echo $e->getMessage();
                exit;
            }

            return redirect(route('adminGetBindPage',[$wxDeviceId]))->with('result', true)->with('msg', "操作成功");
        }


        try{
            $deviceService->bindPage($shakeAroundClient,$objDev,$pageIds,1,0,$objDev->mp->appid,$objDev->mp->appsecret);
        }catch (\Exception $e){
            echo $e->getMessage();
            exit;
        }

        return redirect(route('adminGetBindPage',[$wxDeviceId]))->with('result', true)->with('msg', "操作成功");
    }

    /**
     * GET设置跳转
     * @param $deviceId
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function setRedirect($deviceId)
    {
        return view('admin.wx_device.set_redirect')->with('device_id',$deviceId)->with('device',WeixinDevice::find($deviceId));
    }

    /**
     * 设置跳转 post
     * @param Request $request
     * @param DeviceService $deviceService
     * @return \Illuminate\Http\RedirectResponse
     * @author zhengqian@dajiayao.cc
     */
    public function doSetRedirect(Request $request,DeviceService $deviceService)
    {
        $inputDate = $request->only('name','url','device_id');

        $validator = Validator::make($inputDate,[
           'name'=>'required|max:10',
            'url'=>'required|url',
            'device_id'=>'required|integer'
        ]);

        if( $validator->fails()){
            return redirect(route('adminSetRedirect',[$inputDate['device_id']]))->withInput()->with('result', false)->with('msg', $validator->messages()->first());
        }
        $objWxDevice = WeixinDevice::find($inputDate['device_id']);

        if( ! $objWxDevice){
            return redirect(route('adminSetRedirect',[$inputDate['device_id']]))->withInput()->with('result', false)->with('msg', "找不到微信设备");

        }
        if( ! $deviceService->setRedirect($objWxDevice,$inputDate['url'],$inputDate['name'])){
            return redirect(route('adminSetRedirect',[$inputDate['device_id']]))->with('result', false)->with('msg', "设置失败");
        }

        return redirect(route('adminWxDevicesIndex'))->with('result', true)->with('msg', "设置成功");
    }


    /**
     * 取消重定向
     * @param DeviceService $deviceService
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author zhengqian@dajiayao.cc
     */
    public function unRedirect(DeviceService $deviceService,Request $request)
    {
        $ids = $request->get('id');
        $ids = explode(',',$ids);
        if( ! $ids or !is_array($ids)){
            return redirect(route('adminWxDevicesIndex'))->with('result', false)->with('msg', "请选择设备");
        }

        if($deviceService->unsetRedirect($ids)){
            return redirect(route('adminWxDevicesIndex'))->with('result', true)->with('msg', "设置成功");
        }

    }

    /**修改备注页面
     * @param $deviceId
     * @return $this
     * @author zhengqian@dajiayao.cc
     */
    public function getUpdate($deviceId)
    {
        $device = WeixinDevice::find($deviceId);
        return view('admin.wx_device.update')->with('device',$device);
    }

    /**
     * 修改备注
     * @param ShakeAroundClient $shakeAroundClient
     * @param DeviceService $deviceService
     * @return \Illuminate\Http\RedirectResponse
     * @author zhengqian@dajiayao.cc
     */
    public function update(ShakeAroundClient $shakeAroundClient,DeviceService $deviceService)
    {
        $deviceId = Input::get('device_id');
        $objDevice = WeixinDevice::find($deviceId);
        $comment = Input::get('comment');
        if( ! $comment){
            return redirect(route('adminGetUpdate'))->with('result', false)->with('msg', "备注不能为空");
        }
        $device = new DeviceIdentifier($objDevice->device_id,$objDevice->uuid,$objDevice->major,$objDevice->minor);
        try{
            $deviceService->updateWeixinDevice($device,$shakeAroundClient,$comment,$objDevice->mp->appid,$objDevice->mp->appsecret);
        }catch (\Exception $e){
            return redirect(route('adminWxDevicesIndex'))->with('result', false)->with('msg', $e->getMessage());
        }
        $objDevice->comment = $comment;
        $objDevice->save();
        return redirect(route('adminWxDevicesIndex'))->with('result', true)->with('msg', '操作成功');
    }
}
