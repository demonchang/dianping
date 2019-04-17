<?php 

require_once(dirname(__FILE__).'/../common.php');

$cate = $sql_class->querys('select * from cate where level=0');
$mark = 'ch50';

$cookie = 'gsScrollPos-552=0; gsScrollPos-569=0; gsScrollPos-580=; showNav=#nav-tab|0|0; navCtgScroll=11; gsScrollPos-780=0; gsScrollPos-850=; navCtgScroll=0; gsScrollPos-555=0; gsScrollPos-661=; gsScrollPos-777=0; _lx_utm=utm_source%3DBaidu%26utm_medium%3Dorganic; _lxsdk_cuid=16a2407393d4f-042bff42f1ef1-36647105-1fa400-16a2407393ec8; _lxsdk=16a2407393d4f-042bff42f1ef1-36647105-1fa400-16a2407393ec8; _hc.v=e4decd87-1d61-0d34-ffa0-16d718902362.1555382615; s_ViewType=10; gsScrollPos-386=; cityid=1; default_ab=shop%3AA%3A1; pvhistory=6L+U5ZuePjo8L2Vycm9yL2Vycm9yX3BhZ2U+OjwxNTU1NDczODY2MjgxXV9b; m_flash2=1; cye=pingyu; gsScrollPos-879=; _lxsdk_s=16a2a218603-569-76a-9e3%7C%7C106';

$city = $sql_class->querys('select * from city');
foreach ($city as $key => $value) {
	//$proxys = getProxy();

	$city_id = $value['id'];
	$city_url = $value['url'];
	$area = $sql_class->querys("select * from area where parent_id=".$city_id);	
	/*
	if(empty($area)){
		$url = $city_url.'/'.$mark.'/'.'g'.$cate[0]['url'];
		dump($url,false);
		sleep(1);
		$detail = $curl_class->request($url,$cookie);
		//writeContent($detail);exit();
		$detail_res = pregContent('<div id="region-nav".*?>([\s\S]*?)</div>',$detail);
		if(!empty($detail_res)){
			$out1 = pregAllContent('<a href="'.$url.'(.*?)" data-cat-id="(.*?)".*?><span>(.*?)</span></a>',$detail_res);
			//dump($out1);
			$count1 = count($out1[1]);
			for ($j=0; $j < $count1; $j++) { 
				$name = $out1[3][$j];
				$num = $out1[2][$j];
				$url = $out1[1][$j];

				$cate_field = array(
					'name' => $name,
					'num' => $num,
					'url' => $url
					);
				$area[] = $cate_field;
			}

			foreach ($area as $keys => $values) {
				$name = $values['name'];
				$num = $values['num'];
				$url = $values['url'];
				$content_field = "insert into area (name,url,num,parent_id) value('{$name}','{$url}','{$num}',{$value['id']})";
				//dump($content_field);
				$insert_field = $sql_class->insertContent($url,'area',$content_field);	
			}

		}else{
			saveContent($city_url);
		}

		

		//
	}
	*/
	
	foreach ($cate as $k => $v) {
		
		foreach ($area as $ka => $va) {
			$url_detail = $city_url.'/'.$mark.'/'.'g'.$v['url'].$va['url'];
			//$ip = oneProxy($proxys,$ka);
			//dump($ip,false);
			sleep(1);
			$details = $curl_class->request($url_detail,$cookie);
			
			$page = pregContent('class="PageLink" title=".*?">(.*?)</a>[\s]*?<a.*?class="next"',$details);
			
			
			for ($i=1; $i <= $page; $i++) { 
				if($i == 1){
					 $html = $details;
				}else{
					$url_detail = $city_url.'/'.$mark.'/'.'g'.$v['url'].$va['url'].'p'.$i;
					//sleep(2);
					$html = $curl_class->request($url_detail,$cookie);

				}
				
				$urls = pregAllContent('data-click-name="shop_title_click"[\s\S]*?href="(.*?)"[\s\S]*?<h4>.*?</h4>',$html);
				//dump($urls);
				$count_urls = count($urls[1]);
				dump($url_detail.'-'.$count_urls,false);
				for ($jj=0; $jj < $count_urls; $jj++) { 
					$urlss = $urls[1][$jj];
					$cate_id = $v['id'];
					$area_id = $va['id'];
					$parent_id = $value['id'];
					$content_field = "insert into url (url,city_id,cate_id,area_id) value('{$urlss}',{$parent_id},{$cate_id},{$area_id})";
					//dump($content_field);
					$insert_field = $sql_class->insertContent($urlss,'url',$content_field);	
				}
					
				


			}
		}
		
		
	}
	
	
	

}
		

 ?>