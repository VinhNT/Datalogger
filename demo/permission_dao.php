<?php
require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';
$membership = new Membership();
$membership->confirm_Member();


$permission_mgr = $membership->can_User_Access_System_Settings('permission_mgr');

if ($permission_mgr) {
	$dao = new Dao();
	
	$_data = explode('&', $_GET['data']);
	$op = $_GET['op'];
	
	switch ($op) {
		case 'groupPerm':
			$data = array();
			foreach ($_data as $param) {
				$val = explode('=', $param);
				$val = explode('_', $val[0]);
		
				$data[$val[0].'-'.$val[1]] []= $val[2];
			}
		
			$dao->deletePermissions();
			$dao->savePermissions($data);
		break;
		
		case 'sysPerm':
			$data = array();
			foreach ($_data as $param) {
				$val = explode('=', $param);
				$val = explode('_', $val[0]);
		
				$data[$val[1]] []= $val[0];
			}
			
			foreach ($data as $un => $arrVal) {				
				$dao->updateUserSystemPermissions($un, join(',', $arrVal));
			}
		break;
	}
} 

die;
?>