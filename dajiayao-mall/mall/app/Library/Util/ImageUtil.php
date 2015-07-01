<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/6/5
 * Time: 16:18
 */

namespace Dajiayao\Library\Util;

use \Eventviva\ImageResize;


class ImageUtil
{
    /**
     * 修剪图片的大小
     * @param $originName
     * @param $targetName
     * @param $width
     * @param $height
     * @param bool $allow_enlarge 如果是true则可以将小于原始大小的图片放大
     */
    public static function resizeImage($originName, $targetName, $width, $height, $allow_enlarge = false, $quality = 100)
    {
        if (file_exists($originName)) {
            $image = new ImageResize($originName);
            $image->crop($width, $height, $allow_enlarge);
            $image->save($targetName, null, $quality);
        }
    }



    public static function getRuleImgSize($imageUrl,$width,$height)
    {
        $filePath = public_path(ltrim($imageUrl,'/'));
        $dir = dirname($filePath);
        $arr = explode('.',$filePath);
        $ext = $arr[count($arr)-1];

        $target = sprintf("%s/%s_%sx%s.%s",$dir,str_replace(".$ext",'',basename($filePath)),$width,$height,$ext);
        if( ! is_dir(dirname($target))){
            mkdir(dirname($target),0777);
        }
        if( ! file_exists($target)){
            $quality = 60;
            if (strtolower($ext) == 'png') {
                $quality = 9;
            }
            self::resizeImage($filePath,$target,$width,$height,false,$quality);
        }

        $target = str_replace(public_path(),'',$target);

        return $target;


    }

}