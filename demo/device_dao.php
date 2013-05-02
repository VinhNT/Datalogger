<?php
require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';
require_once '../classes/util.php';
$membership = new Membership();
$membership->confirm_Member();


$access_sys = $membership->can_User_Access_System_Settings();

if ($access_sys) {
	$dao = new Dao();
	
	$_data = @explode('&', $_GET['data']);
	$data = array();
	
	foreach ($_data as $str) {
		$str = explode('=', $str);
		switch ($str[0]) {
			case 'deviceid': 
			case 'groupid':
				$str[1] = str_replace(' ', '', $str[1]);
				$data[$str[0]] = convert($str[1]);
			break;
			default:
				$data[$str[0]] = convert($str[1]);
			break; 
		}
	}
	$data['currentDI'] = $_GET['currentDI'];
	$data['currentGI'] = $_GET['currentGI'];
	
	$op = @$_GET['op'];
	switch ($op) {
		case 'delete_device':
			$deviceid = convert($_GET['deviceid']);
			$dao->removeDevice($deviceid);
		break;
		
		case 'insert_device':
			$dao->addNewDevice($data);
		break;
		
		case 'update_device':
			$dao->updateDevice($data);
		break;
		
		case 'delete_group':
			$groupid = convert($_GET['groupid']);
			$dao->removeGroup($groupid);
		break;
		
		case 'insert_group':
			$dao->addNewGroup($data);
		break;
		
		case 'update_group':
			$dao->updateGroup($data);
		break;
	}
	
	echo 1;
} else {
	echo 0;
}


?>