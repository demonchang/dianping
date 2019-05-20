<?php 

require_once(dirname(__FILE__).'/../common.php');

$start = 0;
$end = 161826;
$page_count = 20;
$page_end = ceil($end/$page_count);





$page_start = 0;

for ($i=0; $i < $page_end; $i++) { 
	$ua = getUa();
	$page_start = ($i+1)*20;
	$filed = array(
	    "pageEnName" => 'shopList',
	    "moduleInfoList" => array(
	    	array(
                'moduleName' => 'mapiSearch',
                'query' => array(
                        'search' => array
                            (
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
	
	$filed = json_encode($filed);
	$content = getPageContent($filed,$ua);
	dump($content);
	

}



function getUa(){
	$ua = [
    "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1",
    "Mozilla/5.0 (X11; CrOS i686 2268.111.0) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",
    "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1092.0 Safari/536.6",
    "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6",
    "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/19.77.34.5 Safari/537.1",
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5"
    ];
	return $ua[array_rand($ua,1)];
}

function getPageContent($filed,$ua){
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://m.dianping.com/isoapi/module",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => $filed,
	  CURLOPT_HTTPHEADER => array(
	    "Accept: */*",
	    "Cache-Control: no-cache",
	    "Connection: keep-alive",
	    "Content-Type: application/json",
	    "Cookie: dper=16004a14fe37ecd00887d5417fa28bd336442b49be7d286b2c6cfa2367a6ca140024d9ebd2223de4fddf0f0fca919efb589a3e62b3755ccb41a7859cf8554d870c36ea5fb5a7f7bf00b5b5495e29f189487706ac3f6b53ab648edfdf02d999b6; cityid=1; msource=default; chwlsource=default; ll=7fd06e815b796be3df069dec7836c3df; _lxsdk_cuid=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; _lxsdk=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; logan_custom_report=; _hc.v=dfba4af7-d2ed-4587-9aaa-07a821c686dd.1558339896; switchcityflashtoast=1; dp_pwa_v_=dc109666dcb29eae1c3ac60dae10d408c495f6a3; source=m_browser_test_33; no_app_installed=yes; default_ab=index%3AA%3A1%7CshopList%3AA%3A1; logan_session_token=ast09ggf8ct14d4l3rvh; _lxsdk_s=16ad44bc0b3-ee-918-898%7C%7C2753,dper=16004a14fe37ecd00887d5417fa28bd336442b49be7d286b2c6cfa2367a6ca140024d9ebd2223de4fddf0f0fca919efb589a3e62b3755ccb41a7859cf8554d870c36ea5fb5a7f7bf00b5b5495e29f189487706ac3f6b53ab648edfdf02d999b6; cityid=1; msource=default; chwlsource=default; ll=7fd06e815b796be3df069dec7836c3df; _lxsdk_cuid=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; _lxsdk=16ad44bc0b1c8-018b9a9ea911fd-36647105-1fa400-16ad44bc0b1c8; logan_custom_report=; _hc.v=dfba4af7-d2ed-4587-9aaa-07a821c686dd.1558339896; switchcityflashtoast=1; dp_pwa_v_=dc109666dcb29eae1c3ac60dae10d408c495f6a3; source=m_browser_test_33; no_app_installed=yes; default_ab=index%3AA%3A1%7CshopList%3AA%3A1; logan_session_token=ast09ggf8ct14d4l3rvh; _lxsdk_s=16ad44bc0b3-ee-918-898%7C%7C2753; msource=default; default_ab=index%3AA%3A1%7CshopList%3AA%3A1",
	    "Host: m.dianping.com",
	    "Postman-Token: 68e318a5-b57f-47ce-81b3-a1d245e1a273,7b40a801-572f-459c-b4f9-8f61b7e488b1",
	    "Referer: https://m.dianping.com/dongguan/ch10/d1?from=m_nav_1_meishi",
	    "User-Agent: ".$ua,
	    "accept-encoding: gzip, deflate",
	    "cache-control: no-cache",
	    "content-length: ".strlen($filed)
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  return "cURL Error #:" . $err;
	} else {
	  return $response;
	}
}





 ?>