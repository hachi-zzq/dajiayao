<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Dajiayao\Services\UserService;

/**
 * 用户
 * @author Haiming
 */
class UserController extends Controller
{


    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return View::make('admin.users.index')
            ->with('users', $users);
    }


    public function toUpdate($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            \App::abort(404, '没有该应用');
        }
        return View::make('admin.users.update')
            ->with('user', $user);
    }

    public function update($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            \App::abort(404, '没有该应用');
        }

        $input = Input::only('email');
        $email = $input['email'];

        if (!$email or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error_tips', '提交数据不完整')->withInput();
        }
        $user->email = $email;
        $user->save();
        return redirect()->route('users')->with("success_tips","操作成功！");
    }

    /**
     * 更新状态
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            \App::abort(404, '没有该用户');
        }
        if ($user->status == User::STATUS_DISABLED) {
            $user->status = User::STATUS_NORMAL;
        } else {
            $user->status = User::STATUS_DISABLED;
        }
        $user->save();
        return redirect()->route('users')->with("success_tips","操作成功！");
    }


    public function toAdd()
    {
        return View::make('admin.users.add');
    }

    public function add()
    {
        $input = \Input::only('username', 'email', 'password', 'role');
        $username = $input['username'];
        $email = $input['email'];
        $role = $input['role'];
        if (($role != User::ROLE_COMMON_USER and $role != User::ROLE_ADMIN) or $username == null or $email == null or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error_tips', '提交的数据不正确')->withInput();
        }
        if (User::isUserNameExist($username)) {
            return redirect()->back()->with('error_tips', '用户名已经存在')->withInput();
        }
        $password = $input['password'];
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = \Hash::make($password);
        $user->role = $role;
        $user->status = User::STATUS_NORMAL;
        $user->save();
        return redirect()->route('users')->with("success_tips","保存成功！");
    }

    public function toUpdatePassword($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            \App::abort(404, '没有该用户');
        }
        if(Auth::user()->id!=$id and Auth::user()->role!=User::ROLE_ADMIN){
            \App::abort(404, '没有该用户或没有权限编辑');
        }
        return View::make('admin.users.update-password')
            ->with('user', $user);
    }

    /**
     * 更新密码
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            \App::abort(404, '没有该用户');
        }
        if(Auth::user()->id!=$id and Auth::user()->role!=User::ROLE_ADMIN){
            \App::abort(404, '没有该用户或没有权限编辑');
        }
        $input = \Input::only('password', 're_password');
        $password = $input['password'];
        $rePassword = $input['re_password'];
        if ($password == null) {
            return redirect()->back()->with('error_tips', '密码不可为空')->withInput();
        }
        if ($password != $rePassword) {
            return redirect()->back()->with('error_tips', '重复密码不一致')->withInput();
        }
        $user->password = \Hash::make($password);
        $user->save();
        return redirect()->back()->with("success_tips","保存成功！");
    }

    public function manualLogin($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            \App::abort(404, '没有该用户');
        }
        Auth::Login($user);
        return redirect('admin');

    }
}
