<?php 

require_once(dirname(__FILE__).'/../common.php');

$proxy_arr = array();
$start = intval(file_get_contents('../log/page.log'));

start($start);

function start($start){
	global $log_class;
	global $curl_class;
	global $sql_class;


	$end = 161826;
	$page_count = 20;
	$page_end = ceil($end/$page_count);
	for ($i=$start; $i < $page_end; $i++) { 
		var_dump($i);
		$ua = getUa();
		$proxy = getIP();
		//dump($ip);
		$page_start = ($i+1)*20;
		$log_class->log($i,'page.log',false);
		$filed = array(
		    "pageEnName" => 'shopList',
		    "moduleInfoList" => array(
		    	array(
	                'moduleName' => 'mapiSearch',
	                'query' => array(
	                        'search' => array(
	                                'start' => $page_start,
	                                'categoryId' => 10,
	                                'parentCategoryId' => 10,
	                                'locateCityid' => 0,
	                                'limit' => 100,
	                                'sortId' => 0,
	                                'cityId' => 219,
	                                'range' => -1,
	                                'maptype' => 0,
	                                'keyword' => ''
	                            )

	                    )

	            )

		    )
		);
		//var_dump($filed);
		$filed = json_encode($filed);
		$content = getPageContent($filed,$ua,$proxy);
		//dump($content);
		if(!$content){
			var_dump('api_error'.$i);
			rmIP($ip);
			sleep(3);
			$start = intval(file_get_contents('../log/page.log'));
			writeContent($local_html,'detail.html',false);
			start($start);
		}
		//writeContent($content,'content.json'); exit();
		//$content = readContent('content.json');
		$content_arr = json_decode($content,true);
		if(!isset($content_arr['code']) && $content_arr['code'] != 200){
			
			var_dump('api_preg_error'.$i);
			rmIP($ip);
			sleep(3);
			$start = intval(file_get_contents('../log/page.log'));
			writeContent($local_html,'detail.html',false);
			start($start);
		}

		$content_queryId = $content_arr['data']['moduleInfoList'][0]['moduleData']['data']['listData']['queryId'];
		$content_list = $content_arr['data']['moduleInfoList'][0]['moduleData']['data']['listData']['list'];
		foreach ($content_list as $key => $value) {
			$ua = getUa();
			$proxy = getIP();

			$shopid = $value['shopId'];
			$shopcontent = $sql_class->querys("select * from content where shopid=".$shopid);	
			if($shopcontent){
				var_dump($shopid.'multi');
				continue;
			}

			https://m.dianping.com/shop/121947521?from=shoplist&shoplistqueryid=c604701e-84d2-40dd-9032-7fb2da975366
			$shop_detail_url = "https://m.dianping.com/shop/".$shopid."?from=shoplist&shoplistqueryid=".$content_queryId;
			sleep(3);
			$detail_html = getShopDetail($shop_detail_url,$ua,$proxy);
			if(empty($detail_html)){
				var_dump('error'.$shop_detail_url);
				writeContent($detail_html);
			}
			//writeContent($detail_html);
			//$detail_html = readContent();
			
			$shopname = pregContent('<h1.*?>(.*?)</h1>',$detail_html);
			
			if(empty($shopname)){
				var_dump('name_error'.$shop_detail_url);
				//continue;
				writeContent($detail_html);
			}

			$address = pregContent('"address":"(.*?)"',$detail_html);
			$tel = pregAllContent('<a class="tel" href="tel:(.*?)"',$detail_html);
			if(!empty($tel[1])){
				$tel = implode($tel[1],',');
			}
			$city = pregContent('<div class="shop-crumbs">[\s]*?<a href=".*?">(.*?)</a>[\s]*?<span class="arrowent"></span>',$detail_html);
			$area = pregContent('<div class="shop-crumbs">[\s]*?<a href=".*?">.*?</a>[\s]*?<span class="arrowent"></span>[\s]*?<a href=".*?">(.*?)</a>[\s]*?<span class="arrowent"></span>',$detail_html);
			$catebig = pregContent('<div class="shop-crumbs">[\s]*?<a href=".*?">.*?</a>[\s]*?<span class="arrowent"></span>[\s]*?<a href=".*?">.*?</a>[\s]*?<span class="arrowent"></span>[\s]*?<a href=".*?">(.*?)</a>[\s]*?',$detail_html);
			$catesmall = pregContent('<div class="shop-crumbs">[\s]*?<a href=".*?">.*?</a>[\s]*?<span class="arrowent"></span>[\s]*?<a href=".*?">.*?</a>[\s]*?<span class="arrowent"></span>[\s]*?<a href=".*?">.*?</a>[\s]*?<span class="arrowent"></span>[\s]*?<a href=".*?">(.*?)</a>',$detail_html);
			

			$shop_local_url = "https://m.dianping.com/shop/".$shopid."/map";
			sleep(2);
			$local_html = getShopDetail($shop_local_url,$ua,$proxy);
			if(empty($local_html)){
				var_dump('local_error'.$shop_local_url);
				rmIP($ip);
				sleep(3);
				$start = intval(file_get_contents('../log/page.log'));
				writeContent($local_html,'detail.html',false);
				start($start);
			}
			//writeContent($local_html);
			//$local_html = readContent();
			//"shopLat":22.94896885911428,"shopLng":113.67237898486616,
			$lat = pregContent('"shopLat":(.*?),',$local_html);
			if(empty($lat)){
				var_dump('lat_error'.$shop_local_url);
				rmIP($ip);
				sleep(3);
				$start = intval(file_get_contents('../log/page.log'));
				writeContent($local_html,'detail.html',false);
				start($start);
				
			}

			$lng = pregContent('"shopLng":(.*?),',$local_html);

			$date = date('Y-m-d H:i:s');

			$content_field = "insert into content(shopid,shopname,address,tel,city,area,catebig,catesmall,lat,lng,date) values('{$shopid}','{$shopname}','{$address}','{$tel}','{$city}','{$area}','{$catebig}','{$catesmall}','{$lat}','{$lng}','{$date}')";
			//var_dump($content_field);
			$select_result = $sql_class->insert($content_field);
			if(!$select_result){
				$log_class->log($content_field,'sql_err.log');
				continue;
			}
			//exit();

		}
	}
}




function getUa(){
	$ua = [
    "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1",
    "Mozilla/5.0 (X11; CrOS i686 2268.111.0) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",
    "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1092.0 Safari/536.6",
    "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6",
    "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/19.77.34.5 Safari/537.1",
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5", 
    'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10',
    'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
    'Mozilla/5.0 (Linux; U; Android 2.3.3; en-au; GT-I9100 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; InfoPath.2; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 1.1.4322)',
    'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1',
    'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
    'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.34 (KHTML, like Gecko) rekonq Safari/534.34',
    'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB6; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; OfficeLiveConnector.1.4; OfficeLivePatch.1.3)',
    'BlackBerry8300/4.2.2 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/107 UP.Link/6.2.3.15.0',
    'IE 7 ? Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',
    'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.23) Gecko/20110920 Firefox/3.6.23 SearchToolbar/1.2',
    'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1',
    'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
    'Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1'
    ];
	return $ua[array_rand($ua,1)];
}

function getIP(){
	global $proxy_arr;
	if(empty($proxy_arr)){
		$res = file_get_contents('http://112.124.117.191/wm/get_proxy.php?count=10');
		$ips = json_decode($res,true);
	}else{
		$ips = $proxy_arr;
	}
	return $ips[array_rand($ips,1)];
}
function rmIP($ip){
	global $proxy_arr;
	foreach ($proxy_arr as $key => $value) {
		if($value == $ip){
			unset($proxy_arr[$key]);
		}
	}
}

function getShopDetail($url,$ua,$proxy){


	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 5,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "Accept: */*",
	    "Cache-Control: no-cache",
	    "Connection: keep-alive",
	    "Cookie: gsScrollPos-546=; gsScrollPos-40=; dper=16004a14fe37ecd00887d5417fa28bd336442b49be7d286b2c6cfa2367a6ca140024d9ebd2223de4fddf0f0fca919efb589a3e62b3755ccb41a7859cf8554d870c36ea5fb5a7f7bf00b5b5495e29f189487706ac3f6b53ab648edfdf02d999b6; ll=7fd06e815b796be3df069dec7836c3df; _lxsdk_cuid=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; _lxsdk=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; logan_custom_report=; _hc.v=dfba4af7-d2ed-4587-9aaa-07a821c686dd.1558339896; switchcityflashtoast=1; dp_pwa_v_=dc109666dcb29eae1c3ac60dae10d408c495f6a3; source=m_browser_test_33; m_flash2=1; cityid=219; default_ab=citylist%3AA%3A1%7Cshop%3AA%3A1%7Cindex%3AA%3A1%7CshopList%3AA%3A1%7Cmap%3AA%3A1; msource=default; logan_session_token=6conve2m92vde5lnq50g; _lxsdk_s=16ae2524d69-e36-5fa-845%7C841394123%7C5; pvhistory=6L+U5ZuePjo8L2Vycm9yL2Vycm9yX3BhZ2U+OjwxNTU4NTc1MjQ0NjU0XV9b",
	    "User-Agent: ".$ua,
	    "accept-encoding: gzip, deflate",
	    "cache-control: no-cache"
	  ),
	));
	if ($proxy) {
		$explode_res = explode("@",$proxy);
		if (!empty($explode_res[1])) {
			curl_setopt($curl, CURLOPT_PROXY,$explode_res[0]);
			curl_setopt($curl, CURLOPT_PROXYUSERPWD,$explode_res[1]);
		}
	}

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  return  false;
	} else {
	  return  $response;
	}
}

function getPageContent($filed,$ua,$proxy){
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://m.dianping.com/isoapi/module",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 5,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => $filed,
	  CURLOPT_HTTPHEADER => array(
	    "Accept: */*",
	    "Cache-Control: no-cache",
	    "Connection: keep-alive",
	    "Content-Type: application/json",
	    "Cookie: gsScrollPos-546=; gsScrollPos-40=; dper=16004a14fe37ecd00887d5417fa28bd336442b49be7d286b2c6cfa2367a6ca140024d9ebd2223de4fddf0f0fca919efb589a3e62b3755ccb41a7859cf8554d870c36ea5fb5a7f7bf00b5b5495e29f189487706ac3f6b53ab648edfdf02d999b6; ll=7fd06e815b796be3df069dec7836c3df; _lxsdk_cuid=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; _lxsdk=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; logan_custom_report=; _hc.v=dfba4af7-d2ed-4587-9aaa-07a821c686dd.1558339896; switchcityflashtoast=1; dp_pwa_v_=dc109666dcb29eae1c3ac60dae10d408c495f6a3; source=m_browser_test_33; m_flash2=1; cityid=219; default_ab=citylist%3AA%3A1%7Cshop%3AA%3A1%7Cindex%3AA%3A1%7CshopList%3AA%3A1%7Cmap%3AA%3A1; msource=default; logan_session_token=6conve2m92vde5lnq50g; _lxsdk_s=16ae2524d69-e36-5fa-845%7C841394123%7C5; pvhistory=6L+U5ZuePjo8L2Vycm9yL2Vycm9yX3BhZ2U+OjwxNTU4NTc1MjQ0NjU0XV9b",
	    "Host: m.dianping.com",
	    "Postman-Token: 68e318a5-b57f-47ce-81b3-a1d245e1a273,7b40a801-572f-459c-b4f9-8f61b7e488b1",
	    "Referer: https://m.dianping.com/dongguan/ch10/d1?from=m_nav_1_meishi",
	    "User-Agent: ".$ua,
	    "accept-encoding: gzip, deflate",
	    "cache-control: no-cache",
	    "content-length: ".strlen($filed)
	  ),
	));
	if ($proxy) {
		$explode_res = explode("@",$proxy);
		if (!empty($explode_res[1])) {
			curl_setopt($curl, CURLOPT_PROXY,$explode_res[0]);
			curl_setopt($curl, CURLOPT_PROXYUSERPWD,$explode_res[1]);
		}
	}

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  return false;
	} else {
	  return $response;
	}
}





 ?>