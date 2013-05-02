<?php 
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	ini_set('memory_limit', '128M');
	
	/* dung cau hinh nay neu chay duoi sub-folder */
	define('SITE_PATH', 'datalogger/'); // doi ten theo sub-folder tuong ung
	
	/* dung cau hinh nay neu chay duoi domain goc hoac sub-domain */
	//define('SITE_PATH', '');


	define('LOGIN_REDIRECT', 'show.php');

	define('DB_SERVER', 'localhost');
	define('DB_NAME', 'datalogger');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'root');

    define('EXPORT_DIR', $_SERVER['DOCUMENT_ROOT'].'/export/temp/csv');
    define('FOLDER_PERMISSION', 0777);

	define('PASSWORD_SALT', '?uM*X|E<Z4Ri81;o)Q}2S1PY2q5gY|x;q@:W;gxWz)l=xH*0bo572NgK&H(5,w2T');
	
	define('MAX_CHART_SAMPLE', 240);
?>