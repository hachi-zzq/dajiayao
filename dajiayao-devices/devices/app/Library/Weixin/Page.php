<?php
/**
 * Created by PhpStorm.
 * User: mynpc
 * Date: 2015/5/6
 * Time: 9:38
 */

namespace Dajiayao\Library\Weixin;


class Page {


    public function __set($k,$v){
        $this->$k = $v;
    }


    function __construct($array)
    {
        foreach($array as $k=>$v){
            $this->$k = $v;
        }

    }

}