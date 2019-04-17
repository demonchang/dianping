<?php 

class Curl {
	public static $agent;
	public function __construct($ua){
		self::$agent = $ua;
	}

	public static function requestAbu($url, $cookie='', $method='get', $fields = array(), $gzip=false, $ua=false, $referer=''){
		// 代理服务器
	    $proxyServer = "http://http-dyn.abuyun.com:9020";

	    // 隧道身份信息
	    $proxyUser   = "H5P6O9840923N97D";
	    $proxyPass   = "680632DD0C156CE3";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//curl_setopt($ch,CURLOPT_HTTPHEADER, array("Host: www.dianping.com" ,'Referer: http://www.dianping.com/pingyu/ch50/g158'));
		//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
		if($ua == false){
			$ua = self::$agent->getOneAgent();
			curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if ($cookie) {
			curl_setopt($ch,CURLOPT_COOKIE,$cookie);
		}
		if ($referer) {
			curl_setopt ($ch,CURLOPT_REFERER, $referer);
		}
		// 设置代理服务器
	    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	    curl_setopt($ch, CURLOPT_PROXY, $proxyServer);

	    // 设置隧道验证信息
	    curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
	    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$proxyUser}:{$proxyPass}");

		if($gzip) curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if ($method === 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, true );
			$fields = http_build_query($fields);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}
		$result = curl_exec($ch);
		return $result;
		curl_close($ch);
	}



	public static function request($url, $cookie='', $proxy='', $method='get', $fields = array(), $gzip=false, $ua=false, $referer=''){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_HTTPHEADER, array("Host: www.dianping.com" ,'Referer: http://www.dianping.com/pingyu/ch50/g158'));
		//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
		if($ua == false){
			$ua = self::$agent->getOneAgent();
			curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		}else{
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)');
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if ($cookie) {
			curl_setopt($ch,CURLOPT_COOKIE,$cookie);
		}
		if ($referer) {
			curl_setopt ($ch,CURLOPT_REFERER, $referer);
		}
		if ($proxy) {
			$explode_res = explode("@",$proxy);
			if (!empty($explode_res[1])) {
				curl_setopt($ch, CURLOPT_PROXY,$explode_res[0]);
				curl_setopt($ch, CURLOPT_PROXYUSERPWD,$explode_res[1]);
			}else{
				curl_setopt($ch, CURLOPT_PROXY, $explode_res[0]);
			}
		}
		if($gzip) curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if ($method === 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, true );
			$fields = http_build_query($fields);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}
		$result = curl_exec($ch);
		return $result;
		curl_close($ch);
	}
	public static function getMultiRequest($url_arr,  $proxy=array(), $gzip=false){
		if (!is_array($url_arr)) {
	        $temp[] = $url_arr;
	        $url_arr = $temp;
	    }
	    $handle = array();
	    $data    = array();
	    $mh = curl_multi_init();
	    $i = 0;
	    $start = rand(1,30);
	    //$url_handle = [];
	    foreach ($url_arr as $key=>$url) {
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, $url);
	            if (!empty($proxy)) {
	            	
					curl_setopt($ch, CURLOPT_PROXY, $proxy[$value]);
					//curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'adsl:public');
	            }
	            //curl_setopt($ch,CURLOPT_HTTPHEADER, array("Host: www.landchina.com" ,'Origin:http://www.landchina.com'));
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');
				//curl_setopt($ch,CURLOPT_COOKIE,'ASP.NET_SessionId=nfcr1mdqwgg2msmkevuk2zaf; Hm_lvt_83853859c7247c5b03b527894622d3fa=1475997978; Hm_lpvt_83853859c7247c5b03b527894622d3fa=1476004958');
	            curl_setopt($ch, CURLOPT_HEADER, 0);
				//curl_setopt ($ch,CURLOPT_REFERER, 'http://www.landchina.com/default.aspx?tabid=261&ComName=default');
	            if($gzip) curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	            //curl_setopt($ch, CURLOPT_PROXY, $proxy);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
	            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	            //curl_setopt($ch, CURLOPT_USERAGENT, self::$agent->getOneAgent());
	            curl_multi_add_handle($mh, $ch); 
	            $handle[$i++] = $ch;
	            //$url_handle[$ch] = $url;       
	        }
	    $active = null;
	    do {
	        $mrc = curl_multi_exec($mh, $active);
	    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
	    while ($active and $mrc == CURLM_OK) {
	        if(curl_multi_select($mh) === -1){
	            usleep(100);
	        }
	        do {
	            $mrc = curl_multi_exec($mh, $active);
	        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
	    }
	    foreach($handle as $j=>$ch) {
	        $content  = curl_multi_getcontent($ch);
	        $url = $url_arr[$j];
	        if (curl_errno($ch) == 0 && $content != '') {
	        	
	            $data[$url] = $content;
	        }else{
	        	$data[$url] = ''; 
	        }
	        
	    }
	    foreach ($handle as $ch) {
	        curl_multi_remove_handle($mh, $ch);
	    }
	    curl_multi_close($mh);
	    //var_dump($data);
	    return $data;//返回抓取到的内同
		}
}




 ?>