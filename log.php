<?php 

	class Log {

		public static $file;
		public static $file_path = './log/';
		public function __construct($filename=''){

			if(empty($filename)){
				$filename = 'default.log';
			}
			self::$file = self::$file_path.$filename;
		}

		public function log($log,$filename='',$append=true){
			if(empty($filename)){
				$file = self::$file;
			}

			$file = self::$file_path.$filename;

			if($append){
				$append = FILE_APPEND;
			}else{
				$append = false;
			}
			file_put_contents($file,$log.PHP_EOL,$append);
		}
	}


 ?>