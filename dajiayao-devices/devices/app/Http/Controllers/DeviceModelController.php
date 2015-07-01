<?php
namespace Dajiayao\Http\Controllers;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\DeviceModel;
use Dajiayao\Services\DeviceModelService;
use Dajiayao\Services\ManufacturerService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

/**
 * 型号管理
 * Class DeviceModelController
 * @package Dajiayao\Http\Controllers
 */
class DeviceModelController extends Controller
{

    function __construct(DeviceModelService $deviceModelService,ManufacturerService $manufacturerService)
    {
        $this->manufacturerService = $manufacturerService;
        $this->deviceModelService = $deviceModelService;
    }

    public function index()
    {
        $deviceModels = $this->deviceModelService->getdeviceModels();
        return View::make('admin.device_models.index')
            ->with('deviceModels', $deviceModels);
    }


    public function toUpdate($id)
    {
        $deviceModel= $this->deviceModelService->getdeviceModelById($id);
        if (!$deviceModel) {
            \App::abort(404, '没有该应用');
        }
        $manufacturers = $this->manufacturerService->getManufacturers();
        return View::make('admin.device_models.update')
            ->with('deviceModel', $deviceModel)
            ->with('manufacturerList', $manufacturers);
    }

    public function update($id)
    {
        $deviceModel = $this->deviceModelService->getdeviceModelById($id);
        if (!$deviceModel) {
            \App::abort(404, '没有该应用');
        }

        $input = Input::only('name', 'manufacturer_id','battery_lifetime','default_password', 'comment');
        $name = $input['name'];
        $manufacturerId = $input['manufacturer_id'];
        $batteryLifetime = $input['battery_lifetime'];
        $defaultPassword = $input['default_password'];
        $comment= $input['comment'];

        $validator = Validator::make($input,[
            'name'=>'required',
            'manufacturer_id'=>'required',
            'battery_lifetime'=>'required|integer'
        ]);
        if($validator->fails() or !$this->manufacturerService->isExist($manufacturerId)){
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $deviceModel->name = $name;
        $deviceModel->manufacturer_id = $manufacturerId;
        $deviceModel->battery_lifetime = $batteryLifetime;
        $deviceModel->default_password = $defaultPassword;
        $deviceModel->comment = $comment;
        $deviceModel->save();
        return redirect()->route('deviceModels')->with("success_tips","操作成功！");
    }


    public function toAdd()
    {
        $manufacturers = $this->manufacturerService->getManufacturers();
        return View::make('admin.device_models.add')
            ->with('manufacturerList', $manufacturers);
    }

    public function add()
    {
        $input = Input::only('name', 'manufacturer_id','battery_lifetime', 'default_password','comment');
        $name = $input['name'];
        $manufacturerId = $input['manufacturer_id'];
        $batteryLifetime = $input['battery_lifetime'];
        $defaultPassword = $input['default_password'];
        $comment= $input['comment'];

        $validator = Validator::make($input,[
            'name'=>'required',
            'manufacturer_id'=>'required',
            'battery_lifetime'=>'required|integer'
        ]);
        if($validator->fails() or !$this->manufacturerService->isExist($manufacturerId)){
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $deviceModel = new DeviceModel();
        $deviceModel->name = $name;
        $deviceModel->manufacturer_id = $manufacturerId;
        $deviceModel->battery_lifetime = $batteryLifetime;
        $deviceModel->default_password = $defaultPassword;
        $deviceModel->comment = $comment;
        $deviceModel->save();
        return redirect()->route('deviceModels')->with("success_tips","保存成功！");
    }

}