<?php
/**
 * @author Hanxiang
 */

namespace Dajiayao\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserKv extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'user_kv';

}
