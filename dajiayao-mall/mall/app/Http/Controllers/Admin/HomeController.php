<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;

/**
 * Class HomeController
 * @package Dajiayao\Http\Controllers\Admin
 * @author Hanxiang
 */
class HomeController extends Controller{

    /**
     * admin 首页
     * @author Hanxiang
     * @return \Illuminate\View\View
     */
    public function index() {
        // TODO
        return view('admin.index');
    }

    public function home() {
        return redirect()->route('adminIndex');
    }

}
