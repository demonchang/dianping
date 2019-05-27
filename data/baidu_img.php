<?php

require_once '../AipOcr.php';

// 你的 APPID AK SK
const APP_ID = '10484689';
const API_KEY = 'h3fVdXkSI6vKKzSKwfX8jlT5';
const SECRET_KEY = '2KLfqk3Gn6OZdQUGUyIInT8GBvM8gOxM';

$client = new AipOcr(APP_ID, API_KEY, SECRET_KEY);

// $url  = 'http://static8.ziroom.com/phoenix/pc/images/price/d4c2fe6d83edbc2ba65f9f05df63f8bcs.png';
// $filename  = download($url);

$image = file_get_contents('./img/WechatIMG64.png');

// 调用通用文字识别（高精度版）
$client->basicAccurate($image);

// 如果有可选参数
$options = array();
$options["detect_direction"] = "false";
$options["probability"] = "false";

// 带参数调用通用文字识别（高精度版）
$res = $client->basicAccurate($image);
$res = $res['words_result'][0]['words'];
var_dump($res);

function download($url){
    $filefold =  './img/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
    $file = curl_exec($ch);

    curl_close($ch);
    if(empty($file)) return false;

    $exf = pathinfo($url, PATHINFO_EXTENSION);
    if(empty($ext))   $exf  = 'png';
    $filename =  date("YmdHis").uniqid().'.'.$exf;
    
    $resource = fopen( $filefold.$filename, 'a');
    fwrite($resource, $file);
    fclose($resource);
    return $filefold.$filename;
}

?>