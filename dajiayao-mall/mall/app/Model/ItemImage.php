<?php namespace Dajiayao\Model;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Hanxiang
 */
class ItemImage extends Model {

    protected $table = 'item_images';

    public $timestamps = false;

    public function image()
    {
        return $this->hasOne('Dajiayao\Model\Image', 'id', 'image_id');
    }
}
