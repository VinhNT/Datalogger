<?php

require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';
require_once '../classes/util.php';
$membership = new Membership();
$membership->confirm_Member();
$dao = new Dao();


$chartMgr = $membership->can_User_Access_System_Settings('chartMgr');

if ($chartMgr) {
	$op = $_GET['op'];
	
	switch ($op) {
		case 'updateChartDetail':
			$_data = @explode('&', $_GET['data']);
			$data = array();
			foreach ($_data as $str) {
				$str = explode('=', $str);
				$data[$str[0]] = convert($str[1]);
			}
			
			$totalItems = $data['totalChartDetail'];
			$curDeviceID = $data['curDeviceID'];
			
			$dao->deleteChartDetail($curDeviceID);
			for ($i=0; $i<$totalItems; $i++) {
				if (!$data["Delete%5B{$i}%5D"]) { // not mark as DELETE
					$status = 0;
					if ($data["Status%5B{$i}%5D"]) $status = 1;
					$chart = array(
						$curDeviceID,
						$data["DataCol%5B{$i}%5D"],
						$data["Type%5B{$i}%5D"],
						$data["Title%5B{$i}%5D"],
						$data["SubTitle%5B{$i}%5D"],
						$data["YAxisLabel%5B{$i}%5D"],
						$data["Order%5B{$i}%5D"],
						$status,
						$data["ChartRange%5B{$i}%5D"]
					);
					$dao->insertChartDetail($chart);
				}
			}
			
			echo 'Update chart successfully!';
		break;
	}
} else {
	echo "Yon don't have permission to access this area!";
	die;
}
?>