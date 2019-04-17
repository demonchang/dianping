<?php 

require_once(dirname(__FILE__).'/../common.php');

$sql = 'select * from url where status=0 limit 100';
$cookie = 'gsScrollPos-552=0; gsScrollPos-569=0; gsScrollPos-580=; showNav=#nav-tab|0|0; navCtgScroll=11; gsScrollPos-780=0; gsScrollPos-850=; navCtgScroll=0; gsScrollPos-555=0; gsScrollPos-661=; gsScrollPos-777=0; _lx_utm=utm_source%3DBaidu%26utm_medium%3Dorganic; _lxsdk_cuid=16a2407393d4f-042bff42f1ef1-36647105-1fa400-16a2407393ec8; _lxsdk=16a2407393d4f-042bff42f1ef1-36647105-1fa400-16a2407393ec8; _hc.v=e4decd87-1d61-0d34-ffa0-16d718902362.1555382615; s_ViewType=10; gsScrollPos-386=; cityid=1; default_ab=shop%3AA%3A1; pvhistory=6L+U5ZuePjo8L2Vycm9yL2Vycm9yX3BhZ2U+OjwxNTU1NDczODY2MjgxXV9b; m_flash2=1; cye=pingyu; gsScrollPos-879=; _lxsdk_s=16a2a218603-569-76a-9e3%7C%7C106';


		
while($rows = $sql_class->querys($sql)){
	//
	
	foreach ($rows as $key => $value) {

		$url_detail = $value['url'];
		
		//$url_detail = 'https://www.dianping.com';
		sleep(1);
		$details = $curl_class->request($url_detail,$cookie);
		dump($details);
		writeContent($details);
	}
}
		

 ?>