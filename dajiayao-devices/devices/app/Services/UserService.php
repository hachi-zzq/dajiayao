<?php namespace Dajiayao\Services;

use Dajiayao\User;
use Validator;
use Auth;

/**
 * 用户操作 Service
 * @author Hanxiang
 */
class UserService {

    /**
     * 登录
     * @author Hanxiang
     * @param string username
     * @param string password
     * @return mixed
     */
    public function login($username, $password) {
        $rules = [
            'username' => ['required', 'min:4', 'max:32'],
            'password' => ['required', 'min:6', 'max:32']
        ];
        $v = Validator::make(['username' => $username, 'password' => $password], $rules);

        if ($v->fails()) {
            return ['result' => false, 'user' => null];
        }

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            return ['result' => true, 'user' => Auth::user()];
        }
        return ['result' => false, 'user' => null];
    }

    /**
     * 登录
     * @author Hanxiang
     * @return mixed
     */
    public function logout() {
        Auth::logout();
        return true;
    }


    public function getUsersByStatus($status){
        return User::getByStatus($status);
    }


    public function getNormalUsers(){
        return $this->getUsersByStatus(User::STATUS_NORMAL);
    }

    public function getAllUsers(){
        return User::all();
    }

    public function getUserById($id){
        return User::getById($id);
    }
}
