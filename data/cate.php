<?php 

require_once(dirname(__FILE__).'/../common.php');


$html = readContent('detail1.html');

$cate = array();

$out = pregAllContent('<a .*?data-cat-id="(\d*?)".*?><span>(.*?)</span></a>',$html);
//dump($out);
$count = count($out[1]);
for ($i=0; $i < $count; $i++) { 
	$name = $out[2][$i];
	$num = $out[1][$i];
	$level = 0;
	$url_detial = 'http://www.dianping.com/shanghai/ch50/g'.$num;
	
	//dump($url_detial);
	//sleep(1);
	$cookie = 'showNav=#nav-tab|0|1; gsScrollPos-552=0; gsScrollPos-569=0; gsScrollPos-580=; navCtgScroll=1; navCtgScroll=0; gsScrollPos-555=0; _lx_utm=utm_source%3DBaidu%26utm_medium%3Dorganic; _lxsdk_cuid=16a2407393d4f-042bff42f1ef1-36647105-1fa400-16a2407393ec8; _lxsdk=16a2407393d4f-042bff42f1ef1-36647105-1fa400-16a2407393ec8; _hc.v=e4decd87-1d61-0d34-ffa0-16d718902362.1555382615; s_ViewType=10; cye=shanghai; cy=1; gsScrollPos-386=; _lxsdk_s=16a25042c19-6df-a0c-493%7C%7C820';
	sleep(2);
	$detail = $curl_class->request($url_detial,$cookie);

	$detail_res = pregContent('<div id="classfy-sub".*?>([\s\S]*?)</div>',$detail);
	if(empty($detail_res)){
		$cate_field = array(
		'name' => $name,
		'num' => $num,
		'level' => $level
		);
		$cate[] = $cate_field;
	}else{
		$cate_field = array(
		'name' => $name,
		'num' => $num,
		'level' => 1
		);
		$cate[] = $cate_field;
		$out1 = pregAllContent('<a.*?data-cat-id="(\d*?)".*?><span>(.*?)</span></a>',$detail_res);
		$count1 = count($out1[1]);
		for ($j=0; $j < $count1; $j++) { 
			$name = $out1[2][$j];
			$num = $out1[1][$j];
			$level = 0;
			$cate_field = array(
				'name' => $name,
				'num' => $num,
				'level' => $level
				);
			$cate[] = $cate_field;
		}

	}
	

}
foreach ($cate as $key => $value) {
	$name = $value['name'];
	$num = $value['num'];
	$level = $value['level'];
	$content_field = "insert into cate (name,url,level) value('{$name}','{$num}','{$level}')";
	//dump($content_field);
	$insert_field = $sql_class->insert($content_field);	
}
	

 ?>