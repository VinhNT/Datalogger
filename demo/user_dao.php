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
	$op = @$_GET['op'];
	$data['currentUN'] = $_GET['currentUN'];
	
	foreach ($_data as $str) {
		$str = explode('=', $str);
		switch ($str[0]) {
			case 'password':
				if ($op == 'updateProfile') {
					if ($str[1] != '') $data[$str[0]] = $membership->hash($str[1]);
				} else {
					$data[$str[0]] = $membership->hash($str[1]);
				}
			break;
			case 'username': 
				$str[1] = str_replace(' ', '', $str[1]);
				$data[$str[0]] = convert($str[1]);
			break;
			default:
				$data[$str[0]] = convert($str[1]);
			break; 
		}
	}
	
	
	
	switch ($op) {
		case 'delete':
			$username = convert($_GET['username']);
			$dao->removeUser($username);
		break;
		
		case 'insert':
			$dao->addNewUser($data);
		break;
		case 'update':
			$dao->updateUser($data);
		break;
		case 'updateProfile':
			$email = $data['email'];
			$curEm = $_GET['currentEM'];
			
			$isEmailExists = false;
			if ($email != $curEm) {
				$isEmailExists = $dao->isEmailExists($data['email'], $curEm);
			}
			
			if ($isEmailExists) {
				echo "{$data['email']} has been taken. Please check again."; 
				exit();
			} else {
				$dao->updateUser($data);
				updateUserSession($data);
			}
		break;
	}
	echo 1;
} else {
	echo 0;
}


function updateUserSession($data){
	$_SESSION['user']->FullName = $data['fullname'];
	$_SESSION['user']->Email = $data['email'];
	$_SESSION['user']->PhoneNumber = $data['phonenumber'];
	$_SESSION['user']->Organization = $data['organization'];
	$_SESSION['user']->Details = $data['detail'];
}
?>