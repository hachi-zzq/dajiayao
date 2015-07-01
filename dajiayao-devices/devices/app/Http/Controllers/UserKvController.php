<?php
namespace Dajiayao\Http\Controllers;

use Dajiayao\Model\App;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Services\AppService;
use Dajiayao\Services\MpService;
use Dajiayao\Services\UserKvService;
use Dajiayao\Services\UserService;
use Dajiayao\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

/**
 * 应用管理
 * Class PageController
 * @package Dajiayao\Http\Controllers
 */
class AUserKvController extends Controller
{

    function __construct(UserKvService $userKvService)
    {
        $this->userKvService = $userKvService;
    }

    public function index($key)
    {
        $kvs= $this->userKvService->getUserKvById($key);
        return View::make('admin/userkv/index')
            ->with('kvs', $kvs);
    }

}