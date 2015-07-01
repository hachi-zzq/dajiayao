<?php
namespace Dajiayao\Http\Controllers;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Model\Manufacturer;
use Dajiayao\Services\ManufacturerService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

/**
 * 厂商管理
 * Class ManufacturerController
 * @package Dajiayao\Http\Controllers
 */
class ManufacturerController extends Controller
{

    function __construct(ManufacturerService $manufacturerService)
    {
        $this->manufacturerService = $manufacturerService;
    }

    public function index()
    {
        $manufacturers = $this->manufacturerService->getManufacturers();
        return View::make('admin.manufacturers.index')
            ->with('manufacturers', $manufacturers);
    }


    public function toUpdate($id)
    {
        $manufacturer= $this->manufacturerService->getManufacturerById($id);
        if (!$manufacturer) {
            \App::abort(404, '没有该应用');
        }

        return View::make('admin.manufacturers.update')
            ->with('manufacturer', $manufacturer);
    }

    public function update($id)
    {
        $manufacturer = $this->manufacturerService->getManufacturerById($id);
        if (!$manufacturer) {
            \App::abort(404, '没有该应用');
        }
        $input = Input::only('name', 'website', 'address', 'email', 'phone', 'comment');
        $name = $input['name'];
        $website = $input['website'];
        $address = $input['address'];
        $email = $input['email'];
        $phone = $input['phone'];
        $comment= $input['comment'];

        if (!($name)) {
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $manufacturer->name = $name;
        $manufacturer->website = $website;
        $manufacturer->address = $address;
        $manufacturer->email = $email;
        $manufacturer->phone = $phone;
        $manufacturer->comment = $comment;
        $manufacturer->save();
        return redirect()->route('manufacturers')->with("success_tips","操作成功！");
    }


    public function toAdd()
    {
        return View::make('admin/manufacturers/add');
    }

    public function add()
    {
        $input = Input::only('name', 'website', 'address', 'email', 'phone', 'comment');
        $name = $input['name'];
        $website = $input['website'];
        $address = $input['address'];
        $email = $input['email'];
        $phone = $input['phone'];
        $comment= $input['comment'];

        if (!($name)) {
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $manufacturer = new Manufacturer();
        $manufacturer->name = $name;
        $manufacturer->website = $website;
        $manufacturer->address = $address;
        $manufacturer->email = $email;
        $manufacturer->phone = $phone;
        $manufacturer->comment = $comment;
        $manufacturer->save();
        return redirect()->route('manufacturers')->with("success_tips","保存成功！");
    }

}