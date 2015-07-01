<?php namespace Dajiayao\Http\Controllers;

use Dajiayao\Library\Util\BrowserUtil;
use Dajiayao\User;
use \Input;
use Dajiayao\Services\UserService;

/**
 * 用户
 * @author Hanxiang
 */
class UserController extends Controller {

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 登录页面
     * @author Hanxiang
     */
    public function login() {
        if(BrowserUtil::getBrowser() =='ie' and BrowserUtil::getBrowserVer()< 9){
            return view('errors.error-browser');
        }
        return view('login');
    }

    /**
     * 登录post
     * @author Hanxiang
     */
    public function loginPost() {
        $input = Input::only('username', 'password');
        $service = $this->userService;
        $login = $service->login($input['username'], $input['password']);
        if ($login['result']) {
            if ($login['user']->role == User::ROLE_ADMIN) {
                return redirect()->route('adminIndex');
            } else {
                if($login['user']->status==User::STATUS_DISABLED){
                    //TODO 此处需优化，先判断是否被锁定再决定是否登录。
                    $service->logout();
                    return redirect()->route('login')->with('error', true)->with('err_msg', '该用户已被冻结');
                }
                return redirect()->route('index');
            }
        }

        return redirect()->route('login')->with('error', true)->with('err_msg', '账号名或密码错误');
    }

    /**
     * 登出
     * @author Hanxiang
     */
    public function logout() {
        $service = $this->userService;
        $service->logout();
        return redirect('login');
    }
}
