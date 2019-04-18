<?php 

require_once(dirname(__FILE__).'/../common.php');

$sql = 'select * from url where status=0 limit 100';
$cookie = ''; // 带上cookie要每次更新。要不然cookie会被识别
		
while($rows = $sql_class->querys($sql)){

	foreach ($rows as $key => $value) {
		$id = $value['id'];
		$url_detail = $value['url'];
		//dump($url_detail,false);
		sleep(7);
		$html = $curl_class->request($url_detail,$cookie);
		if(empty($html)){
			$status = 2; //内容获取失败
			$content_fieldss = "update url set status={$status} where id={$id}";
			$sql_class->update($content_fieldss);
			continue;
		}
		//dump($details);
		//writeContent($html);
		//$html = readContent();
		$title = pregContent('<h1 class="shop-name">([\s\S]*?)<a',$html);
		if(empty($title)){
			$status = 3; //标题解析错误
			$content_fieldss = "update url set status={$status} where id={$id}";
			$sql_class->update($content_fieldss);
			continue;
		}

		$address = pregContent('<span class="item" itemprop="street-address" title="([\s\S]*?)"',$html);
		$tel = formatContent(pregContent('<p class="expand-info tel">[\s]*?<span class="info-name">电话：</span>([\s\S]*?)</p>',$html),true);
                
        $business = pregContent('营业时间：</span>[\s]*?<span class="item">([\s\S]*?)</span>',$html);  
        $price = pregContent('<span class="item">消费：([\s\S]*?)</span>',$html);
  		$effect = pregContent('<span class="item">效果：([\s\S]*?)</span>',$html);
  		$environment = pregContent('<span class="item">环境：([\s\S]*?)</span>',$html);
  		$service = pregContent('<span class="item">服务：([\s\S]*?)</span>',$html);
  		$comment = pregContent('<span class="item">([\s\S]*?)条评论</span>',$html);
  		$city = pregContent('itemprop="url">([\s\S]*?)丽人[\s]*?</a>',$html);
  		$area = pregContent('<div id="body" class="body">[\s\S]*?&gt;[\s]*?<a.*?>([\s\S]*?)</a>[\s]*?&gt;[\s]*?<a.*?>[\s\S]*?</a>[\s]*?&gt;[\s]*?<a.*?>[\s\S]*?</a>[\s]*?&gt;',$html);
    	$road = pregContent('<div id="body" class="body">[\s\S]*?&gt;[\s]*?<a.*?>[\s\S]*?</a>[\s]*?&gt;[\s]*?<a.*?>([\s\S]*?)</a>[\s]*?&gt;[\s]*?<a.*?>[\s\S]*?</a>[\s]*?&gt;',$html);
    	$industry = pregContent('<div id="body" class="body">[\s\S]*?&gt;[\s]*?<a.*?>[\s\S]*?</a>[\s]*?&gt;[\s]*?<a.*?>[\s\S]*?</a>[\s]*?&gt;[\s]*?<a.*?>([\s\S]*?)</a>[\s]*?&gt;',$html);
		
		$date = date('Y-m-d H:i:s');

		$content_field = "insert into detail(title,address,tel,business,price,effect,environment,service,comment,city,area,road,industry,parent_id,date) values('{$title}','{$address}','{$tel}','{$business}','{$price}','{$effect}','{$environment}','{$service}','{$comment}','{$city}','{$area}','{$road}','{$industry}',{$id},'{$date}')";
		$select_result = $sql_class->insert($content_field);

		if($select_result){
			$status = 1; //正确
		}else{
			$status = 4; //插入失败
			$log_class->log($content_field,'sql_err.log');
		}
		
		$content_fieldss = "update url set status={$status} where id={$id}";
		$sql_class->update($content_fieldss);

		
	}
}
		

 ?>