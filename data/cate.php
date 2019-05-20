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
	$url_detial = 'http://www.dianping.com/dongguan/ch10/g'.$num;
	
	//dump($url_detial);
	//sleep(1);
	$cookie = 'navCtgScroll=0; showNav=#nav-tab|0|1; navCtgScroll=11; _lxsdk_cuid=16a2f5b852b45-0b86a76d832f57-36647105-1fa400-16a2f5b852c36; _lxsdk=16a2f5b852b45-0b86a76d832f57-36647105-1fa400-16a2f5b852c36; _hc.v=9b6f3a0e-4afd-68c6-f693-159ccbd4754a.1555572689; s_ViewType=10; _lx_utm=utm_source%3DBaidu%26utm_medium%3Dorganic; cy=219; cye=dongguan; lgtoken=0fde2fccc-6bf1-483f-ba04-20467b09e79d; _lxsdk_s=16ad42ef910-c07-dfb-a67%7C%7C782';
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