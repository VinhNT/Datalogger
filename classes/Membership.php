<?php

require_once 'conf.php';
require_once 'dao.php';

class Membership {
	private $sys_superuser = array('admin');
	private $systemPermssions = array(
		'userMgr' => 'Manage user',
		'deviceMgr' => 'Manage device',
		'permissionMgr' => 'Manage permission',
		'chartMgr' => 'Manage chart'
	);
	
	public function validate_user($un, $pwd) {
		session_start();
		$dao = new Dao();
		$user = $dao->getUserByUnAndPwd($un, $this->hash($pwd));

		if($user !== false)
		{
			$_SESSION['status'] = 'authorized';
			$_SESSION['user'] = $user;

			header("location: demo/".LOGIN_REDIRECT); 
			exit(1);
		} else return "Please enter a correct username and password";
		
	} 
	
	public function log_User_Out() {
		session_start();
		if(isset($_SESSION['status'])) {

			unset($_SESSION['status']);
			unset($_SESSION['user']);

			if(isset($_COOKIE[session_name()])) 
				setcookie(session_name(), '', time() - 1000);
				session_destroy();
		}
	}
	
	public function confirm_Member() {
		session_start();
		if($_SESSION['status'] !='authorized') header("location: ../login.php");
	}
	
	public function hash($pwd) {
		$pwd = substr(md5($pwd.PASSWORD_SALT), 0, 25).substr(md5(PASSWORD_SALT),0,5);

		return $pwd;
	}
	
	public function can_User_Access_System_Settings($perm='') {
		if(session_id() == '') { 
			session_start(); 
		}
		// alway allow admin
		if (in_array($_SESSION['user']->UserName, $this->sys_superuser)) return true;

		$userPerm = $_SESSION['user']->SystemPermission;
		$userPerm = explode(',', $userPerm);
		if ($perm == '') {
			foreach ($userPerm as $p) {
				if (array_key_exists($p, $this->systemPermssions)) return true;
			}
		} else {
			
			if (in_array($perm, $userPerm)) return true;
		}
		
		
		return false;
	}
	
	public function getSystemPermission(){
		return $this->systemPermssions;
	}

}


?>