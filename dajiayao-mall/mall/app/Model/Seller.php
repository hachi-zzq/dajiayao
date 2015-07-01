<?php namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $foo
 * @property mixed auth_status
 * @author Hanxiang
 */
class Seller extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'sellers';

    protected $_wxUser;


    const AUTH_STATUS_NONE = 0;
    const AUTH_STATUS_SUCCESS = 1;

    public function shop()
    {
        return $this->hasOne('Dajiayao\Model\Shop', 'seller_id', 'id');
    }

    public function wxUser()
    {
        if ($this->_wxUser) {
            return $this->_wxUser;
        }
        $this->_wxUser = $this->hasOne('Dajiayao\Model\WxUser', 'id', 'wx_user_id');
        return $this->_wxUser;
    }

    public static function getAll()
    {
        return self::orderBy('updated_at')->get();
    }

    public static function getById($id)
    {
        return self::where('id',$id)->first();
    }

    public static function getAllWithPage($page)
    {
        return self::orderBy('updated_at')->paginate($page);
    }

    public function getAuthStatusName()
    {
        if ($this->auth_status == self::AUTH_STATUS_SUCCESS) {
            return "通过";
        }
        return "未认证";
    }

}
