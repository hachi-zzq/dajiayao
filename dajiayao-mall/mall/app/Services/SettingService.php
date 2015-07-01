<?php namespace Dajiayao\Services;

use Dajiayao\Model\Setting;

/**
 * 配置操作 Service
 * @author Haiming
 */
class SettingService
{
    /**
     * 更新某配置项目
     * @param $key
     * @param $value
     */
    public function updateSetting($key, $value)
    {
        $item = Setting::getByKey($key);
        $item->value = $value;
        $item->save();
    }

    public function getAllSetting()
    {
        return Setting::getAll();
    }

    public function getSettingByKey($key)
    {
        return Setting::getByKey($key);
    }




//    /**
//     * 批量更新配置项
//     * @param array $keys
//     * @param array $values
//     */
//    public function updateSettings(array $keys,array $values){
//        $items = Setting::getByKeys($keys);
//        $len = count($items);
//        foreach($keys as $index=>$key){
//            if($len>=$index){
//                if($items[$index]->value!=$values[$index]){
//                    $items[$index]->value =
//                }
//            }
//        }
//    }

}
