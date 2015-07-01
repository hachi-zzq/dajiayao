<?php namespace Dajiayao\Http\Controllers;

use Dajiayao\Http\Requests;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $url = "http://resource.feng.com/resource/h054/h85/img201505200140510_130__84.png";
        file_get_contents($url);
		return view('home');
	}

    /**
     * 默认跳转页面
     * @return string
     * @author zhengqian@dajiayao.cc
     */
    public function defaultPage()
    {
        return "this is a default page";
    }
}
