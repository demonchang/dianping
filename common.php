<?php 

require_once(dirname(__FILE__).'/curl.php');
require_once(dirname(__FILE__).'/log.php');
require_once(dirname(__FILE__).'/ua.php');
require_once(dirname(__FILE__).'/function.php');
require_once(dirname(__FILE__).'/mysqls.php');
date_default_timezone_set('Asia/Shanghai');

$agent_class = new Agent();
$curl_class = new Curl($agent_class);
$log_class = new Log();
$sql_class = new Mysqlis();



 ?>