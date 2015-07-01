<?php namespace Dajiayao\Http\Controllers;

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
            if ($login['user']->role == 1) {
                return redirect('admin');
            } else {
                if($login['user']->status==User::STATUS_DISABLED){
                    //TODO 此处需优化，先判断是否被锁定再决定是否登录。
                    $service->logout();
                    redirect('login')->with('error', true)->with('err_msg', '该用户已被冻结');
                }
                return redirect('/');
            }
        }

        return redirect('login')->with('error', true)->with('err_msg', '账号名或密码错误');
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
