<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\App;
use Dajiayao\Model\Device;
use Dajiayao\Model\DeviceApp;
use Dajiayao\Model\DeviceModel;
use Dajiayao\Model\Manufacturer;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Library\Util\SNMaker;
use Dajiayao\Model\WeixinMp;
use Illuminate\Support\Facades\Config;
use Input;
use PHPPdf\Core\Configuration\LoaderImpl;
use PHPPdf\Core\FacadeBuilder;
use PHPQRCode\Constants;
use PHPQRCode\QRcode;

class DeviceController extends Controller {

    /**
     * 设备列表
     * @author Hanxiang
     */
    public function index() {
        // get devices by status
        $input = Input::all();
        if (!isset($input['status']) || $input['status'] == -1) {
            $devices = Device::where('status', '>=', 0);//->paginate(15);
            $status = -1;
        } else {
            $status = $input['status'];
            $devices = Device::where('status', $status);//->paginate(15);
        }

        if (isset($input['app_id']) && !empty($input['app_id'])) {
            $app_id = $input['app_id'];
            $device_ids = [];
            $device_apps = DeviceApp::where('app_id', $app_id)->get()->toArray();
            foreach ($device_apps as $da) {
                array_push($device_ids, $da['device_id']);
            }
            $devices = $devices->whereIn('id', $device_ids);
        }

        if (isset($input['sn']) && !empty($input['sn'])) {
            $devices = $devices->where('sn', 'like', '%' . $input['sn'] . '%');
        }

        if (isset($input['uuid']) && !empty($input['uuid'])) {
            $devices = $devices->where('uuid', 'like', '%' . $input['uuid'] . '%');
        }

        if (isset($input['major']) && !empty($input['major'])) {
            $devices = $devices->where('major', $input['major']);
        }

        if (isset($input['minor']) && !empty($input['minor'])) {
            $devices = $devices->where('minor', $input['minor']);
        }

        if (isset($input['date_from']) && !empty($input['date_from'])) {
            $devices = $devices->where('created_at', '>', self::convtDate($input['date_from']));
        }

        if (isset($input['date_to']) && !empty($input['date_to'])) {
            $devices = $devices->where('created_at', '<', self::convtDate($input['date_to'], false));
        }

        $devices = $devices->orderBy('updated_at','desc')->get();//->paginate(15);

        foreach($devices as $device) {
            $device->status_cn = Device::$status[$device->status];

            if ($device->status > 0) {
                $deviceApp = DeviceApp::where("device_id", $device->id)->first();
                if ($deviceApp) {
                    $device->app = DeviceApp::find($deviceApp->id)->app;
                } else {
                    $app_empty = new \stdClass();
                    $app_empty->name = '';
                    $device->app = $app_empty;
                }
            }
        }

        $manufacturers = Manufacturer::getAllWithModels();

        // get all apps
        $apps = App::all();
        return view('admin.devices')
            ->with('devices', $devices)
            ->with('status', $status)
            ->with('apps', $apps)
            ->with('manufacturers', $manufacturers);
    }

    /**
     * 增加SN
     * @author Hanxiang
     */
    public function addPost() {
        $input = Input::only('count');
        if ((int)$input['count'] <= 0) {
            return redirect('/admin/devices')->with('result', false)->with('msg', "操作失败");
        }

        for ($i = 1; $i <= $input['count']; $i++) {
            $sn = SNMaker::getSN();
            $d = Device::where('sn', $sn)->first();
            if ($d) {
                $i--;
                continue;
            }

            $device = new Device();
            $device->model_id = 0;
            $device->manufacturer_sn = '';
            $device->sn = $sn;
            $device->wx_device_id = 0;
            $device->uuid = '';
            $device->major = 0;
            $device->minor = 0;
            $device->status = 0;
            $device->save();
        }

        return redirect('/admin/devices');
    }

    /**
     * 给设备分配应用
     * @author Hanxiang
     */
    public function alloc() {
        $input = Input::only('id', 'app_ids', 'ids');
        if (empty($input['app_ids'])) {
            return response()->json(['result' => 0, 'msg' => "操作失败，请选择一个应用"]);
        }

        if (isset($input['id']) && !empty($input['id'])) {
            $device_id = $input['id'];
            $device = Device::find($device_id);
            if (!$device) {
                return response()->json(['result' => 0, 'msg' => "操作失败，请选择一个可用的设备"]);
            }
            if ($device->status == 1 || $device->status == 2) {
                return response()->json(['result' => 1, 'msg' => "操作成功"]);
            }
            foreach ($input['app_ids'] as $app_id) {
                // 新增关联表记录
                $deviceApp = new DeviceApp();
                $deviceApp->device_id = $device_id;
                $deviceApp->app_id = (int)$app_id;
                $deviceApp->save();
            }

            // 修改 apps 和 devices
            $device->status = 1;
            $device->save();
        }
        if (isset($input['ids']) && !empty($input['ids'])) {
            foreach ($input['ids'] as $did) {
                $device = Device::find($did);
                if (!$device) {
                    return response()->json(['result' => 0, 'msg' => "操作失败，请选择一个可用的设备"]);
                }
                if ($device->status == 1 || $device->status == 2) {
                    continue;
                }
                foreach ($input['app_ids'] as $app_id) {
                    // 新增关联表记录
                    $deviceApp = new DeviceApp();
                    $deviceApp->device_id = (int)$did;
                    $deviceApp->app_id = (int)$app_id;
                    $deviceApp->save();
                }
                $device->status = 1;
                $device->save();
            }
        }

        return response()->json(['result' => 1, 'msg' => "操作成功"]);
    }

    /**
     * 获取厂商数据ajax
     * @author Hanxiang
     */
    public function manufacturersAjax() {
        $input = Input::only('id');
        $manufacturers = Manufacturer::find($input['id']);
        if (!$manufacturers) {
            return response()->json(['mans' => [], 'models' => []]);
        }

        $models = DeviceModel::where('manufacturer_id', $input['id'])->get();
        if (count($models) > 0) {
            foreach ($models as $model) {
                $batteryOutDate = date("m/d/Y", strtotime($model->created_at) + (int)$model->battery_lifetime * 2952000);
                $model->battery_outdate = $batteryOutDate;
                $model->generated_at = self::reconvtDate(date($model->created_at));
            }
        } else {
            $models = new \stdClass();
        }
        $manufacturers->models = $models;
        return response()->json($manufacturers);
    }

    /**
     * 设备烧号
     * @author Hanxiang
     */
    public function burnin() {
        $input = Input::all();
        if (!isset($input['uuid'])) {
            return response()->json(['result' => false]);
        }

        $device = Device::find($input['id']);
        if (!$device) {
            return response()->json(['result' => false]);
        }

        $wxDevice = WeixinDevice::find($input['wx_device_id']);
        if (!$wxDevice) {
            return response()->json(['result' => false]);
        }

        $device->model_id = $input['model_id'];
        $device->manufacturer_sn = $input['man_sn'];
        $device->uuid = $input['uuid'];
        $device->major = $input['major'];
        $device->minor = $input['minor'];
        $device->wx_device_id = $input['wx_device_id'];
        $device->password = $input['password'];
        $device->power_outage_date = self::convtDate($input['battery_expire']);//
        $device->status = 2;
        $device->comment = $wxDevice->comment;
        $device->save();
        return response()->json(['result' => true]);
    }

    /**
     * 获取微信设备AJAX
     * @author Hanxiang
     */
    public function wxdevicesAjax() {
        $input = Input::all();
        $sn = $input['sn'];
        $device = Device::where('sn', $sn)->first();
        if (!$device) {
            return response()->json(['wx_devices' => [], 'first' => new \stdClass()]);
        }
        $app_id = DeviceApp::where('device_id', $device->id)->lists('app_id');
        $wx_mp_id = WeixinMp::where('app_id', $app_id)->lists('id');
        $wxdevices = WeixinDevice::where('wx_mp_id', $wx_mp_id)->get();
        if (count($wxdevices) > 0) {
            foreach ($wxdevices as $wx) {
                $wx->sn = Device::where("wx_device_id", $wx->id)->get();
            }
        }

        $firstWxDevice = self::getFirstAvailableWxDevice();

        return response()->json([
            'wx_devices' => $wxdevices->toArray(),
            'first' => $firstWxDevice
        ]);
    }

    /**
     * 转换日期格式
     * @author Hanxiang
     * @param string $date 05/15/2015
     * @param boolean $flag
     * @return string 2015-05-15
     */
    private static function convtDate($date, $flag = true) {
        $month = substr($date, 0, 2);
        $day = substr($date, 3, 2);
        $year = substr($date, 6);
        if ($flag) {
            return "$year-$month-$day 00:00:00";
        } else {
            return "$year-$month-$day 23:59:59";
        }
    }
    /**
     * 转换日期格式
     * @author Hanxiang
     * @param string $date 2015-05-15 12:34:56
     * @return string 05/15/2015
     */
    private static function reconvtDate($date) {
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        $year = substr($date, 0, 4);
        return "$month/$day/$year";
    }

    /**
     * 导出 PDF
     * @author Hanxiang
     */
    public function genPDF() {
        $input = Input::only('ids');

        if (sizeof($input['ids']) == 0) {
            return response()->json(['result' => 0, 'link' => "", 'msg' => '请选择要导出的设备']);
        }
        $snList = Device::findSNsByIds($input['ids']);
        $count = count($snList);
        $loader = new LoaderImpl();
        $loader->setFontFile(public_path("pdf/style/fonts.xml"));
        $facade = FacadeBuilder::create($loader)
            ->setEngineType('pdf')
            ->build();

        $documentFilename = public_path("pdf/style/table.xml");
        $stylesheetFilename = public_path("pdf/style/table-style.xml");

        if (!is_readable($documentFilename)) {
            return response()->json(['result' => 0, 'link' => ""]);
        }
        if (!is_readable($stylesheetFilename)) {
            return response()->json(['result' => 0, 'link' => ""]);
        }
        $i = 0;
        $xml = simplexml_load_file($documentFilename);
        $table = $xml->xpath('/pdf/dynamic-page/div/table')[0];
        $pageCount = (int)($count / 80) + 1;
        for ($p = 0; $p < $pageCount; $p++) {
            $tr = $table->addChild('tr');
            $tr['class']='head-foot';
            for ($t = 0; $t < 10; $t++) {
                $td = $tr->addChild('td');
                if ($t == 0 or $t == 9) {
                    $td['class'] = "top-left-right";
                } else {
                    $td['class'] = "top-bottom";
                }
            }

            for ($j = 0; $j < 10; $j++) {
                $tr = $table->addChild('tr');
                for ($t = 0; $t < 10; $t++) {
                    $td = $tr->addChild('td');
                    if ($t == 0 or $t == 9) {
                        $td['class'] = "left-right";
                    } else {
                        $td['class']='content';
                        if ($i < $count) {
                            $sn = $snList[$i];
                            $qrName = public_path() . "/pdf/qr/url_qr_" . $sn . ".png";
                            QRcode::png(Config::get("app.QR_URL") . '?sn=' . $sn, $qrName, Constants::QR_ECLEVEL_M, 4, 0);
                            $this->fillTd($td, "SN:" . $sn, $qrName);
                            $i++;
                        } else {
                            $div = $td->addChild('div');
                            $div->addChild('span', '');
                        }
                    }

                }
            }

            $tr = $table->addChild('tr');
            $tr['class']='head-foot';
            for ($t = 0; $t < 10; $t++) {
                $td = $tr->addChild('td');
                if ($t == 0 or $t == 9) {
                    $td['class'] = "bottom-left-right";
                } else {
                    $td['class'] = "top-bottom";
                }
            }
        }





        $newName = "pdf/documents/" . time();
        $tableXmlStr = $xml->asXML();

//        $stylesheetXml =  file_get_contents($stylesheetFilename);
//        $stylesheet = $stylesheetXml ? \PHPPdf\DataSource\DataSource::fromString($stylesheetXml) : null;

        $content = $facade->render($tableXmlStr, $stylesheetFilename);

        $newFilename = $newName . ".pdf";
        file_put_contents(public_path($newFilename), $content);

        return response()->json(['result' => 1, 'link' => url($newFilename)]);
    }
    /**
     * 填充TD
     * @param \SimpleXMLElement $td
     * @param $sn
     * @param $imgPath
     */
    private function fillTd(\SimpleXMLElement &$td, $sn, $imgPath)
    {
        $div = $td->addChild('div');
        $div->addChild('span', $sn);
        $div->addChild('img')['src']=$imgPath;
    }

    /**
     * 获取当前可用的设备信息ajax
     * @author Hanxiang
     * TODO
     */
    public function currentAvailableDeviceAjax() {

        $input = Input::only('id');
        $device = Device::find($input['id']);
        if (!$device) {
            return response()->json(['result' => 0, 'device' => null, 'wx_devices' => null]);
        }

        $firstWxDevice = self::getFirstAvailableWxDevice();

        return response()->json([
            'result' => 1,
            'device' => $device->toArray(),
            'wx_devices' => $firstWxDevice
        ]);
    }

    /**
     * 获取第一个可用的微信设备
     * @author Hanxiang
     */
    private static function getFirstAvailableWxDevice() {
        $wxdevices = WeixinDevice::all();
        if (count($wxdevices) > 0) {
            foreach ($wxdevices as $wx) {
                $wx->sn = Device::where("wx_device_id", $wx->id)->get();
            }
        }

        $usedWxDeviceIDs = Device::where("wx_device_id", ">", 0)->get(['wx_device_id']);
        $usedIDArray = [];
        if (count($usedWxDeviceIDs) > 0) {
            foreach ($usedWxDeviceIDs as $usedID) {
                array_push($usedIDArray, $usedID['wx_device_id']);
            }
        }

        $firstWxDevice = WeixinDevice::whereNotIn('id', $usedIDArray)->first();
        if (count($firstWxDevice) > 0) {
            $firstWxDevice = $firstWxDevice->toArray();
        } else {
            $firstWxDevice = [];
        }

        return $firstWxDevice;
    }
}
