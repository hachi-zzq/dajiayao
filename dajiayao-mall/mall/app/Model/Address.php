<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author Hanxiang
 */
class Address extends Model {

    public $timestamps = false;

    protected $table = 'addresses';


    public function getFather()
    {
        return self::find($this->parent_id);
    }
}
