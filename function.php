<?php 

/**
 * 正则匹配字符串
 * @param preg string 要匹配的正则内容
 * @param html string 字符串内容
 * @param only bool  单一匹配直接返回结果，否则返回匹配到的所有内容数组  默认单一匹配 
 * @param debug bool 是否开启调试模式  默认关闭
 * @param symbol bool 正则使用的包裹符号，默认单引号，否则使用双引号
 */

function pregContent($preg, $html, $only=true, $debug=false, $single_quotes = true ){
	if(!$single_quotes){
		preg_match("#".$preg."#",$html,$out);
	}else{
		preg_match('#'.$preg.'#',$html,$out);
	}

	if($debug){
		return $out;
	}

	if($only){
		if(isset($out[1]) && !empty($out[1])){
			return trim($out[1]);
		}
	}else{
		if(isset($out[0]) && !empty($out[0])){
			unset($out[0]);
			return $out;
		}
	}
	//detault retutn false;
	return false;
}

/**
 * 正则匹配所有符合规则的字符串
 * @param preg string 要匹配的正则内容
 * @param html string 字符串内容
 * @param debug bool 是否开启调试模式  默认关闭
 * @param symbol bool 正则使用的包裹符号，默认单引号，否则使用双引号
 */

function pregAllContent($preg, $html, $debug=false, $single_quotes = true ){
	if(!$single_quotes){
		preg_match_all("#".$preg."#",$html,$out);
	}else{
		preg_match_all('#'.$preg.'#',$html,$out);
	}

	if($debug){
		return $out;
	}


	if(isset($out[0]) && !empty($out[0])){
		unset($out[0]);
		return $out;
	}
	//detault retutn false;
	return false;
}


function writeContent($html, $file_name='detail.html',$stop=true){
	file_put_contents($file_name,$html);
	if($stop) exit();
}
function readContent($file_name='detail.html'){
	return file_get_contents($file_name);

}

function formatContent($content ,$del_html=false, $any_space=false){
	if(empty($content)) return false;

	if(is_array($content)){
		$new_arr = array();
		foreach ($content as $key => $value) {
			if(is_array($value)){
				$new_arr[$key] = formatContent($value,$del_html,$any_space);
			}else{
				$res = trim($value);
				if($del_html) $res = preg_replace('#<[\s\S]*?>#','',$res);
				if($any_space) $res = preg_replace('#[\s]*?#','',$res);
				$new_arr[$key] = $res;
			}
		}
		return $new_arr;
	}else{
		$res = trim($content);
		if($del_html) $res = preg_replace('#<[\s\S]*?>#','',$res);
		if($any_space) $res = preg_replace('#[\s]*?#','',$res);
		return $res;
	}
}

function saveContent($content, $file_name='result.csv'){
	file_put_contents($file_name,$content.PHP_EOL,FILE_APPEND);
}

function dump($result, $stop=true){
	var_dump($result);
	if($stop) exit();
}

function jsonToCsv($json, $file_name='default.csv', $append_str=''){
	saveContent($json,$file_name.'.json');
	$arr = json_decode($json,true);
	$str = arrToStr($arr,$append_str);
	saveContent($str,$file_name);
}


function oneProxy($proxys,$key){
	$proxys_count = count($proxys);
	$num = $key%$proxys_count;
	return $proxys[$num];
}

function arrToStr($arr, $append_str='', $mult=false){
	$str = '';
	foreach ($arr as $key => $value) {
		if(is_array($value)){
			$str .= arrToStr($value,$append_str,true);
		}else{
			$str .= $value.',';

		}
	}
	if($append_str){
		$str .= $append_str;
	}else{
		$str = substr($str,0,-1);
	}

	if($mult){
		$str = $str.PHP_EOL;
	}
	return $str;
}



 ?>