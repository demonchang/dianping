<?php 

require_once(dirname(__FILE__).'/../common.php');


$html = file_get_contents('detail.html');



$out = pregAllContent('<a  href="(.*?)/ch0" class="link onecity">(.*?)</a>',$html);

$count = count($out[1]);
for ($i=0; $i < $count; $i++) { 
	$url = 'http:'.$out[1][$i];
	$spell = substr(strrchr($url,'/'),1);
//	dump($spell);
	$name = $out[2][$i];


	$content_field = "insert into city (url,spell,name) value('{$url}','{$spell}','{$name}')";
	//var_dump($content_field);exit();
	$insert_field = $sql_class->insertContent($url,'city',$content_field);
	
	
	

}

		

 ?>