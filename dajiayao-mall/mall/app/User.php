<?php namespace Dajiayao;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    const ROLE_SUPPLIER = 2;
    const ROLE_ADMIN = 1;

    const STATUS_NORMAL = 0;
    const STATUS_DISABLED = -1;



    public static function getByStatus($status)
    {
        return self::where('status', $status)->get();
    }

    public static function getById($id)
    {
        return self::where('id', $id)->first();
    }

    public function userRoleName()
    {
        if ($this->role == self::ROLE_ADMIN) {
            return "管理员";
        }
        if ($this->role == self::ROLE_SUPPLIER) {
            return "普通用户";
        }
    }

    public function statusName()
    {
        if ($this->status == self::STATUS_DISABLED) {
            return "冻结";
        }
        if ($this->status == self::STATUS_NORMAL) {
            return "正常";
        }
    }

    public static function isUserNameExist($name)
    {
        return self::where('username', $name)->count() > 0;

    }

}
