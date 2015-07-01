<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'users';


    public function app()
    {
        return $this->hasMany('Dajiayao\Model\App','user_id');
    }

}
