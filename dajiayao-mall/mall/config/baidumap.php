<?php
$baiduAK = "AfGhcdbGUoiVdvxtaMkrodKU";

return [
  'api'=>[
      'address'=>"http://api.map.baidu.com/geocoder/v2/?address=%s&output=json&ak=$baiduAK",
      'inverse_address'=>"http://api.map.baidu.com/geocoder/v2/?ak=$baiduAK&location=%s,%s&output=json"
  ]
];