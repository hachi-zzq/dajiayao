<?php
/**
 * Created by PhpStorm.
 * User: minco
 * Date: 15/5/13
 * Time: 11:07
 */

namespace Dajiayao\Library\Util;


class SNMaker
{

    /**
     * 生成SN编号
     * 分为四段 共12位：
     * 第一段 一位 保留字段 默认为0
     * 第二段 三位：生成日期，第一位表示年、第二位表示月、第三位表示日。用字每顺序表示数字，A代表1、B代表2、C代表3，Z代表26以此类推，1代表27、2代表28、3代表29、4代表30、5代表31。
     * 第三段 五位随机数:  从00000-99999 （后续可用来扩展流水号等）
     * 第四段 三位随机数：000-ZZZ
     * 例如：2015年5月13日生成的一条条形码:0PFN93151DQV
     * @return string
     */
    public static function getSN()
    {
        $a = '0';
        $dicArray = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $b = $dicArray[intval(idate('y'))] . $dicArray[intval(idate('m'))] . $dicArray[intval(idate('d'))];
        $c = sprintf("%05d", rand(0, 99999));
        $d = '';
        for ($i = 0; $i < 3; $i++) {
            $d .= $dicArray[rand(0, 35)];
        }

        return $a . $b . $c . $d;
    }
}


